#!/usr/bin/env python3
"""
Simple smoke-test script for a website.

Checks a list of paths (and optionally crawls internal links) for HTTP errors
and common error strings in the response body, then prints a short summary.

Usage:
  python tests/smoke_test.py --base-url https://example.com --max-pages 10
"""
from __future__ import annotations
import argparse
import sys
import time
import urllib.request
import urllib.parse
from html.parser import HTMLParser
from urllib.error import URLError, HTTPError
from collections import deque
from typing import Set, List, Tuple, Optional


class LinkParser(HTMLParser):
    def __init__(self, base: str):
        super().__init__()
        self.base = base
        self.links: Set[str] = set()

    def handle_starttag(self, tag, attrs):
        if tag.lower() != 'a':
            return
        for (k, v) in attrs:
            if k.lower() == 'href' and v:
                self._add_link(v)

    def _add_link(self, href: str) -> None:
        if href.startswith('mailto:') or href.startswith('javascript:') or href.startswith('#'):
            return
        absolute = urllib.parse.urljoin(self.base, href)
        base_netloc = urllib.parse.urlparse(self.base).netloc
        # only keep same-site links
        if urllib.parse.urlparse(absolute).netloc == base_netloc:
            cleaned = absolute.split('#')[0]
            self.links.add(cleaned)


def fetch(url: str, timeout: int = 10) -> Tuple[Optional[int], str]:
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            code = resp.getcode()
            body = resp.read()
            try:
                text = body.decode('utf-8', errors='replace')
            except Exception:
                text = body.decode('latin-1', errors='replace')
            return code, text
    except HTTPError as e:
        # HTTPError has a status code and may include a body
        try:
            body = e.read()
            text = body.decode('utf-8', errors='replace')
        except Exception:
            text = ''
        return e.code, text
    except URLError as e:
        return None, str(e)


def body_has_error_markers(text: str) -> List[str]:
    markers = [
        'Fatal error',
        'Warning:',
        'Exception',
        'Traceback',
        '500 Internal Server Error',
        '502 Bad Gateway',
        '503 Service Unavailable',
    ]
    found = []
    for m in markers:
        if m.lower() in text.lower():
            found.append(m)
    return found


def normalize_path(base: str, path: str) -> str:
    if not path:
        return base
    return urllib.parse.urljoin(base, path)


def main():
    p = argparse.ArgumentParser(description='Run a small smoke-test sweep of a website')
    p.add_argument('--base-url', '-b', default='http://localhost:8000', help='Base URL to test')
    p.add_argument('--paths', '-p', nargs='*', default=['/'], help='Extra paths to test (space-separated)')
    p.add_argument('--max-pages', '-m', type=int, default=20, help='Maximum number of pages to test (including crawled)')
    p.add_argument('--timeout', type=int, default=10, help='Request timeout in seconds')
    p.add_argument('--verbose', '-v', action='store_true', help='Show per-request details')
    args = p.parse_args()

    base = args.base_url.rstrip('/') + '/'
    initial_urls = [normalize_path(base, p) for p in args.paths]

    queue = deque(initial_urls)
    visited: Set[str] = set()
    passed = 0
    failed = 0
    failures: List[Tuple[str, str]] = []

    start = time.time()
    while queue and len(visited) < args.max_pages:
        url = queue.popleft()
        if url in visited:
            continue
        visited.add(url)
        if args.verbose:
            print(f'Checking {url} ...')

        code, text = fetch(url, timeout=args.timeout)
        reason = ''
        ok = True
        if code is None:
            ok = False
            reason = f'Network error: {text}'
        elif code >= 400:
            ok = False
            reason = f'HTTP {code}'
        else:
            markers = body_has_error_markers(text or '')
            if markers:
                ok = False
                reason = 'Body markers: ' + ', '.join(markers)

        if ok:
            passed += 1
            if args.verbose:
                print(f'  OK ({code})')
        else:
            failed += 1
            failures.append((url, reason))
            print(f'  FAIL: {url} -> {reason}')

        # crawl internal links from this page
        if text:
            parser = LinkParser(url)
            try:
                parser.feed(text)
                for l in sorted(parser.links):
                    if l not in visited and l not in queue and len(visited) + len(queue) < args.max_pages:
                        queue.append(l)
            except Exception:
                # parsing shouldn't break the run
                pass

    elapsed = time.time() - start
    total = passed + failed
    print('\n--- Summary ---')
    print(f'Tested pages: {total}')
    print(f'Passed: {passed}')
    print(f'Failed: {failed}')
    print(f'Time: {elapsed:.1f}s')
    if failures:
        print('\nFailures:')
        for u, r in failures:
            print(f'- {u} -> {r}')

    # exit non-zero when there are failures
    sys.exit(0 if failed == 0 else 2)


if __name__ == '__main__':
    main()
