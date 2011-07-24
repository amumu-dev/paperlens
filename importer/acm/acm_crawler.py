#!/usr/bin/python
# -*- coding: UTF-8 -*- 
from common import *
from page_processor import *


# CrawlPage and write to file
def CrawlPage(segs,file_fp):
	ret_url_list = []
	url = segs[2]
	type_t = segs[0]
	journal = segs[1]
	page = DownloadPage(url)
	url_list = []
	p = re.compile('<td nowrap="nowrap"><a href="(citation\.cfm\?id=[0-9]*&CFID=[0-9]*&CFTOKEN=[0-9]*)" >.*</a>')
	
	temp_list = p.findall(page)
	if len(temp_list) == 0:
		print "page has no target content url=[%s]"%(url)
	for item in temp_list:
		url_list.append('http://portal.acm.org/' + item + "&preflayout=flat")
	
	# processing url per year
	idx = 0
	p1 = re.compile('<a href="(citation\.cfm\?id=[0-9]*&CFID=[0-9]*&CFTOKEN=[0-9]*)">.*</a></span>')
	for url in url_list:
		idx += 1
		page = DownloadPage(url)
		temp_list = p1.findall(page)
		print "processing [%d]  url=[%s] target=[%d]"%(idx,url,len(temp_list))
		if len(temp_list) == 0:
			print "page has no target content url=[%s]"%(url)
		for item in temp_list:
			url = "http://portal.acm.org/" + item + "&preflayout=flat"
			#ret_url_list.append(type_t + '\t' + journal + '\t' + url)
			page = DownloadPage(url)
			result = PageProcess(type_t,journal,page)
			file_fp.write('\t'.join(result)+'\n')

# preprocess csv file to get url and important other info like type and journal
def PreProcessCSV(csv_file,out_seed_file):
	file_fp = open(out_seed_file,'w')
	is_fist_line = True
	for line in file(csv_file):
		if is_fist_line:
			is_fist_line = False
			continue
		segs = line.strip('\n|\r').split('","')
		if 9 != len(segs):
			print '[Warning] line[%s] format error'%(line)
			continue
		type_t = segs[0].strip('"')
		journal = segs[1].strip('"')
		url= segs[8].strip('"') + g_url_pre 
		if not url.startswith('http://portal.acm.org/citation'): continue
		file_fp.write("%s\t%s\t%s\n"%(type_t,journal,url))	
	file_fp.close()


def Usage():
	print >> sys.stderr,'python acm_crawler.py csv_file thread_num thread_no!'
	sys.exit(-1)


if __name__ == "__main__":
	# preprocess csv file to get seed file
   	if 4 != len(sys.argv):
		Usage()
	csv_file = sys.argv[1]
	thread_num = int(sys.argv[2])
	thread_no = int(sys.argv[3])

	PreProcessCSV(csv_file,g_out_seed_file)	
	
	file_fp = open('data/citation_%d.txt'%(thread_no),'w')
	idx = 0
	for line in file(g_out_seed_file):
		if idx%thread_num != thread_no:
			idx += 1
			continue
		segs = line.strip('\n').split('\t')
		url = segs[2]
		print "processing line [%d] p_no = [%d] url=[%s]"%(idx,thread_no,url)
	 	CrawlPage(segs,file_fp)

	file_fp.close()

