# -*- coding: utf-8 -*-
import crawler
import xml.dom.minidom
import urllib
import codecs
import time
import os

def GetFeedLink(id_node):
    p = id_node.firstChild.data.find('reader/feed/') + len('reader/feed/')
    return id_node.firstChild.data[p:]

spider = crawler.Crawler("")

def IsChinese(buf):
    if buf.find('的') > 0 or buf.find('了') > 0:
        return True
    if buf.find('ス') > 0 or buf.find('ッ') > 0 or buf.find('モ') > 0 or buf.find('ェ') > 0 or buf.find('ス') > 0:
        return False
    return False

def CrawlUser(user_id):
    atom = spider.download("http://www.google.com/reader/public/atom/user/" + str(user_id) + "/state/com.google/broadcast?n=50")
    if IsChinese(atom) == False:
        return set()
    if len(atom) == 0:
        return set()
    lines = atom.split('\n')
    dom = xml.dom.minidom.parseString(str.strip(atom))
    entries = dom.getElementsByTagName("entry")
    related_users = set()
    tm = time.strftime('%Y-%m-%d',time.localtime(time.time()))
    sw = codecs.open('googlereader.txt', 'a', 'utf-8')
    sw.write('<user id=\"' + user_id + '\">\n')
    for entry in entries:
        sw.write('\t<entry>\n')
        link = entry.getElementsByTagName("link")[0].toxml()
        sw.write('\t\t' + link + '\n')
        title = entry.getElementsByTagName("title")[0].toxml()
        sw.write('\t\t' + title + '\n')
        src_node = entry.getElementsByTagName("source")[0]
        sw.write('\t\t' + src_node.toxml() + '\n')
        likers = entry.getElementsByTagName("gr:likingUser")
        for liker in likers:
            related_users.add(liker.firstChild.data)
            sw.write('\t\t<liker>' + liker.firstChild.data + '</liker>\n')
        categories = entry.getElementsByTagName("category")
        for category in categories:
            sw.write('\t\t' + category.toxml() + '\n')
        sw.write('\t</entry>\n')
    sw.write('</user>\n')
    sw.close()
    return related_users

def CrawlAll():
    Q = []
    if os.path.isfile('googlereader_users.txt'):
        sr = open('googlereader_users.txt','r')
        for line in sr:
            Q.append(str.strip(line))
        sr.close()
    if len(Q) == 0:
        Q = ['06601636036055060713']
    visited = set()
    k = 0
    while len(Q) > 0:
        k += 1
        if k > 1000:
            break
        user_id = Q.pop()
        if user_id in visited:
            continue
        print k, user_id
        visited.add(user_id)
        users = CrawlUser(user_id)
        #time.sleep(2)
        for uid in users:
            if uid in visited:
                continue
            Q.append(uid)
    sw = open('googlereader_users.txt', 'w')
    for uid in Q:
        sw.write(uid + '\n')
    sw.close()

CrawlAll()
