import sys
sys.path.append("../")
import paperlens_import
import crawler
import xml.dom.minidom
import time
import random
import string
import math
from operator import itemgetter

def GetUsers(buf):
    ret = set()
    p1 = 0
    while 1:
        p1 = buf.find("http://www.citeulike.org/user/", p1)
        if p1 < 0:
            break
        p1 = p1 + len("http://www.citeulike.org/user/")
        p2 = buf.find("/", p1 + 1)
        if p2 < 0 or p2 > p1 + 20:
            break
        ret.add(buf[p1:p2])
        p1 = p2 + 1
    return ret

def GetCategories(buf):
    ret = dict()
    p1 = 0
    while 1:
        p1 = buf.find("<prism:category>", p1)
        if p1 < 0:
            break
        p1 = p1 + len("<prism:category>")
        p2 = buf.find("</prism:category>", p1 + 1)
        if p2 < 0 or p2 > p1 + 50:
            break
        word = buf[p1:p2]
        if word not in ret:
            ret[word] = 1
        else:
            ret[word] = ret[word] + 1
        p1 = p2 + 1
    return ret


all_categories = ["collaborative filtering"]
processed_categories = set()
c = crawler.Crawler("")
while len(all_categories) > 0:
    word = all_categories.pop()
    if word in processed_categories:
        continue
    processed_categories.add(word)
    print word
    xml = c.download("http://www.citeulike.org/rss/search/all?q=" + string.replace(word, " ", "+"))
    fp = open('./words/' + string.replace(word, " ", "+") + '.xml', 'w')
    fp.write(xml)
    fp.close()
    users = GetUsers(xml)
    categories = GetCategories(xml)
    file_user = open('users.txt','a')
    for user in users:
        file_user.write(user + "\n")
    file_user.close()
    k = 0
    for word,weight in sorted(categories.items(), key=itemgetter(1), reverse=True):
        if weight < 2 or len(word) < 8 or k > 3 or word in processed_categories:
            continue
        all_categories.append(word)
        k = k + 1
    time.sleep(random.uniform(15,30))
