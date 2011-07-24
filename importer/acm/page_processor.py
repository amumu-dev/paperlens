#!/usr/bin/python
import sys
import urllib2,cookielib
import re

publish_regx = re.compile('<meta name="citation_publisher" content="([^\"]*)">')
author_regx = re.compile('<meta name="citation_authors" content="([^\"]*)">')
title_regx = re.compile('<meta name="citation_title" content="([^\"]*)">')
year_regx = re.compile('<meta name="citation_date" content="([^\"]*)">')
school_regx = re.compile("<td valign=\"bottom\">[\s\S]*?<small>(.*?)</small>[\s\S]*?</td>")
abstract_regx = re.compile('<h1 class=\"mediumb-text\">[\s\S]*?ABSTRACT</A></h1>[\s\S]*?<div style=[\s\S]*?>(.*?)</div>')

def PageProcess(type_t,journal,txt):
	ret_list = []
	
	publish_dict = publish_regx.findall(txt)
	publish = publish_dict[0] if len(publish_dict) > 0 else ''

	author_dict = author_regx.findall(txt)
	author = author_dict[0] if len(author_dict) > 0 else ''

	title_dict = title_regx.findall(txt)
	title = title_dict[0] if len(title_dict) > 0 else ''

	year_dict = year_regx.findall(txt)
	year = year_dict[0] if len(year_dict) > 0 else ''

	school_dict = school_regx.findall(txt)
	school = school_dict[0] if len(school_dict) > 0 else ''
	
	abstract_dict = abstract_regx.findall(txt)
	abstract= abstract_dict[0] if len(abstract_dict) > 0 else ''


	ret_list.append(title)
	ret_list.append(author)
	ret_list.append(type_t)
	ret_list.append(year)
	ret_list.append('') # booktitle
	ret_list.append(journal)
	ret_list.append(school)
	ret_list.append(publish)
	ret_list.append(abstract)
	return ret_list

#if __name__ == '__main__':
#	p_no = int(sys.argv[1])
#	file_fp = open('data/acm/acm_citation_info_%d.txt'%(p_no),'w')
#	for line in file('data/citation_%s'%(p_no)):
#		segs = line.strip('\n').split('\t')
#		page = DownloadPage(segs[2])
#		l = PageProcess(segs,page)
#		file_fp.write('\t'.join(l)+'\n')
#	file_fp.close()
