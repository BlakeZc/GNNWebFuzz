from browsermobproxy import Server
from pathlib import Path 
driver_path = str(Path(__file__).parent.absolute()) + '/webFuzz/drivers/browsermob-proxy-2.1.4/bin/browsermob-proxy.bat'
print(driver_path)
server = Server(driver_path, options={'port': 8090})
server.start()
proxy = server.create_proxy()

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service

chrome_options = Options()
print(proxy.proxy)
service = Service(r"C:\\Program Files\\Google\\Chrome\Application\\chromedriver.exe")
print(service)
chrome_options.add_argument('--proxy-server={0}'.format(proxy.proxy))
chrome_options.add_argument('--ignore-certificate-errors')
chrome_options.add_experimental_option("excludeSwitches", ["ignore-certificate-errors"])
 
driver = webdriver.Chrome(service=service, options=chrome_options)

proxy.new_har("my_baidu", options={'captureHeaders': True, 'captureContent': True})

driver.get('http://192.168.30.137/wordpress_instrumented/wp-admin/install.php')

import time 
time.sleep(5)

result = proxy.har
for rs in result['log']['entries']:
    print(rs['request']['method'], rs['request']['url'])

with open('test.har','w',encoding='utf-8') as f:
    f.write(str(result))
    print('ok')