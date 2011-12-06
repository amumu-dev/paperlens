import MySQLdb
import crawler
import xml.dom.minidom
import time
import random

def GetFeedInfo(url):
    c = crawler.Crawler('')
    rss = c.download(url)
    if len(rss) < 20:
        return ['', '']
    try:
        dom = xml.dom.minidom.parseString(str.strip(rss))
        items = dom.getElementsByTagName('item')
        title = ''
        link = ''
        description = ''
        for item in items:
            title_node = item.getElementsByTagName('title')
            if len(title_node) > 0:
                title = title_node[0].firstChild.data
            link_node = item.getElementsByTagName('link')
            if len(link_node) > 0:
                link = link_node[0].firstChild.data
            break
    except xml.parsers.expat.ExpatError, e:
        return ['', '']
    except AttributeError, e:
        return ['', '']
    return [title, link]
        

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "reader", charset="utf8")
cursor = connection.cursor()
cursor.execute("set names utf8")


try:
    data = open("feed_popularity.txt")
    for line in data:
        [feed, title, popularity] = line.split('\t')
        print feed, popularity
        cursor.execute("insert into feeds(name, link, popularity) values (%s,%s,%s) on duplicate key update popularity=values(popularity);",
                       (title, feed, popularity))
    data.close()
    
    cursor.execute("select link from feeds where modify_at>%s;", (int(time.mktime(time.localtime())) - 10000))
    numrows = int(cursor.rowcount)
    feeds = set()
    for k in range(numrows):
        row = cursor.fetchone()
        feeds.add(row[0])
    print 'new feed number : ', len(feeds)
    n = 0
    data = open("feed_popularity.txt")
    for line in data:
        if random.random() > 0.2:
            continue
        [feed, title, popularity] = line.split('\t')
        if feed in feeds:
            print 'up to date', feed
            continue
        [article_title, article_link] = GetFeedInfo(feed)
        if len(article_title) == 0:
            continue
        print feed, article_title, article_link
        cursor.execute("replace into feeds(name, link, popularity,latest_article_title,latest_article_link,modify_at) values (%s,%s,%s,%s,%s,%s);",
                       (title, feed, popularity, article_title, article_link, int(time.mktime(time.localtime()))))
        
    connection.commit()
    cursor.close()
    connection.close()
    data.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
