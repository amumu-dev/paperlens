import sys
sys.path.append("../")
import os
import paperlens_import
import crawler
import xml.dom.minidom
import time
import random
import string
import math
from operator import itemgetter

def GetQuery(word):
    ret = string.replace(word, " ", "+")
    ret = string.replace(ret, "-", "+")
    ret = string.replace(ret, "_", "+")
    return ret

def getProcessedUsers():
    ret = set()
    fp = open("pusers.txt")
    for line in fp:
        ret.add(line.strip())
    return ret

outfile = open("users.xml", "a")
processed_user_file = open("pusers.txt", "a")
##for path, subdirs, files in os.walk('./users/'):
##    for userfile in files:
##        filename = os.path.join(path, userfile)
##        user = str.replace(filename, './users/', '')
##        user = str.replace(user, '.xml','')
##        outfile.write('###' + user + '\n')
##        fp = open(filename);
##        for line in fp:
##            outfile.write(line)
##        fp.close()
##        processed_user_file.write(user + '\n')
##
##processed_user_file.close()
##outfile.close()

userfile = open("users.txt")
users = set()
lines = userfile.readlines()
for line in lines:
    users.add(line.strip())
processed_users = getProcessedUsers()
print len(processed_users)
print len(users)
c = crawler.Crawler("")
k = 0
for user in users:
    if user in processed_users:
        continue
    print k,user
    try:
        xml = c.download("http://www.citeulike.org/rss/user/" + user)
    except:
        continue
    outfile.write('###' + user + '\n')
    outfile.write(xml)
    if len(xml) < 100:
        continue
    processed_user_file.write(user + '\n')
    outfile.flush()
    processed_user_file.flush()
    k = k + 1
    if random.uniform(0,10) == 1:
        time.sleep(random.uniform(10,20))

processed_user_file.close()
outfile.close()

##all_categories = ["temporal+recommendation"]
##processed_categories = set()
##c = crawler.Crawler("")
##while len(all_categories) > 0:
##    word = all_categories.pop()
##    if word in processed_categories:
##        continue
##    processed_categories.add(word)
##    print word
##    xml = c.download("http://www.citeulike.org/rss/search/all?q=" + word)
##    fp = open('./words/' + word + '.xml', 'w')
##    fp.write(xml)
##    fp.close()
##    users = GetUsers(xml)
##    categories = GetCategories(xml)
##    file_user = open('users.txt','a')
##    for user in users:
##        file_user.write(user + "\n")
##    file_user.close()
##    k = 0
##    for word,weight in sorted(categories.items(), key=itemgetter(1), reverse=True):
##        if weight < 2 or len(word) < 8 or k > 3 or word in processed_categories:
##            continue
##        all_categories.append(word)
##        k = k + 1
##    time.sleep(random.uniform(5,10))
