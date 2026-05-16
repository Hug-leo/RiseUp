import urllib.request
import urllib.parse
import json
import os
import subprocess
import time

PROVINCE_DATA = 'wordpress/wp-content/themes/charity-hcm/data/province-directory.json'
IMAGE_DIR = 'wordpress/wp-content/themes/charity-hcm/assets/img/provinces'
REPORT_FILE = '/tmp/wiki_province_image_report.json'

os.makedirs(IMAGE_DIR, exist_ok=True)

def get_url_data(url):
    try:
        headers = {'User-Agent': 'Mozilla/5.0'}
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=30) as response:
            return response.read()
    except Exception as e:
        print(f"Error fetching {url}: {e}")
        return None

def get_wiki_image(province_name):
    api_url = 'https://vi.wikipedia.org/w/api.php'
    
    variants = [
        province_name,
        f'{province_name} (tỉnh)',
        f'Tỉnh {province_name}',
    ]
    if 'TP.' in province_name:
        city_name = province_name.replace('TP. ', '')
        variants.insert(0, city_name)
        variants.append(f'Thành phố {city_name}')

    for title in variants:
        quoted_title = urllib.parse.quote(title)
        params = f'?action=query&format=json&prop=pageimages&titles={quoted_title}&piprop=original&redirects=1'
        data = get_url_data(api_url + params)
        if data:
            r = json.loads(data)
            pages = r.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                if pid != '-1' and 'original' in pdata:
                    return pdata['original']['source'], pdata['title']
    
    # Search fallback
    quoted_search = urllib.parse.quote(f'{province_name} tỉnh')
    search_params = f'?action=query&format=json&list=search&srsearch={quoted_search}&srlimit=1'
    search_data = get_url_data(api_url + search_params)
    if search_data:
        r = json.loads(search_data)
        search_results = r.get('query', {}).get('search', [])
        if search_results:
            top_title = search_results[0]['title']
            quoted_top_title = urllib.parse.quote(top_title)
            params = f'?action=query&format=json&prop=pageimages&titles={quoted_top_title}&piprop=original&redirects=1'
            data = get_url_data(api_url + params)
            if data:
                r = json.loads(data)
                pages = r.get('query', {}).get('pages', {})
                for pid, pdata in pages.items():
                    if pid != '-1' and 'original' in pdata:
                        return pdata['original']['source'], pdata['title']
    return None, None

def process_image(url, slug):
    local_temp = f'/tmp/{slug}_temp'
    output_path = os.path.join(IMAGE_DIR, f'{slug}.jpg')
    try:
        img_data = get_url_data(url)
        if not img_data: return False
        with open(local_temp, 'wb') as f:
            f.write(img_data)
        
        # Initial convert and resize
        subprocess.run(['sips', '-s', 'format', 'jpeg', '--resampleHeightWidthMax', '1400', local_temp, '--out', output_path], capture_output=True)
        
        # Iterative compression
        quality = 90
        while os.path.getsize(output_path) > 170 * 1024 and quality > 10:
            subprocess.run(['sips', '-s', 'formatOptions', str(quality), output_path], capture_output=True)
            quality -= 10
        
        # If still too large, resize to 1000px and compress again
        if os.path.getsize(output_path) > 170 * 1024:
            subprocess.run(['sips', '--resampleHeightWidthMax', '1000', output_path], capture_output=True)
            quality = 80
            while os.path.getsize(output_path) > 170 * 1024 and quality > 10:
                subprocess.run(['sips', '-s', 'formatOptions', str(quality), output_path], capture_output=True)
                quality -= 10

        if os.path.exists(local_temp):
            os.remove(local_temp)
        return True
    except Exception as e:
        print(f"Error processing {slug}: {e}")
        return False

with open(PROVINCE_DATA, 'r') as f:
    data = json.load(f)

report = []
success_count = 0
fail_names = []

for key, province in data.items():
    name = province['name']
    slug = province['slug']
    print(f"Processing {name}...")
    img_url, wiki_title = get_wiki_image(name)
    status = 'failed'
    if img_url:
        if process_image(img_url, slug):
            status = 'success'
            success_count += 1
        else:
            fail_names.append(name)
    else:
        fail_names.append(name)
    report.append({'name': name, 'slug': slug, 'wiki_title_used': wiki_title, 'image_url': img_url, 'status': status})
    time.sleep(0.1)

with open(REPORT_FILE, 'w') as f:
    json.dump(report, f, indent=2)

print(f'\nSummary:')
print(f'Total provinces: {len(data)}')
print(f'Success: {success_count}')
print(f'Failure: {len(fail_names)}')
if fail_names:
    print(f'Failed names: {", ".join(fail_names)}')

print("\nSample Mappings:")
for item in report[:5]:
    print(f"{item['name']} -> {item['wiki_title_used']}")
