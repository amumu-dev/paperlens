#!/usr/bin/envy python
# -*- coding: UTF-8 -*- 
import sys
import urllib2,cookielib
import re


#url pre foer downloading
g_url_pre = '&preflayout=flat'

# out seed filepath
g_out_seed_file = 'data/acm_seed.list'

# user agent
g_user_agent = "Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Ubuntu/10.10 Chromium/10.0.648.133 Chrome/10.0.648.133 Safari/534.16"

# download page
def DownloadPage(url):
	request = urllib2.Request(url)
	request.add_header('User-Agent',g_user_agent)
	return urllib2.urlopen(request).read()


