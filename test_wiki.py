import urllib.request
import urllib.parse
import json

def get_wiki_image(province_name):
    api_url = 'https://vi.wikipedia.org/w/api.php'
    quoted_title = urllib.parse.quote(province_name)
    params = f'?action=query&format=json&prop=pageimages&titles={quoted_title}&piprop=original&redirects=1'
    url = api_url + params
    print(f"URL: {url}")
    try:
        headers = {'User-Agent': 'Mozilla/5.0'}
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req) as response:
            data = response.read()
            r = json.loads(data)
            print(json.dumps(r, indent=2))
    except Exception as e:
        print(f"Error: {e}")

get_wiki_image("Hà Nội")
