#!/bin/bash

# acm url csv
readonly acm_csv_url="http://portal.acm.org/feeds/dl_contents.csv"
# user agent for wget 
readonly user_agent="Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Ubuntu/10.10 Chromium/10.0.648.133 Chrome/10.0.648.133 Safari/534.16"
# data dir 
readonly data_dir="data"
# thread num
readonly thread_num=2

mkdir -p $data_dir

# download seed list csv
wget $acm_csv_url -q --user-agent=$user_agent -O $data_dir/acm_url.csv

# main python 
for (( i=0; i<=$thread_num; i++ ))
do
	echo "Thread $i start ..."
	python shell/acm_crawler.py $data_dir/acm_url.csv $thread_num $i >/dev/null 2>&1 &
done
