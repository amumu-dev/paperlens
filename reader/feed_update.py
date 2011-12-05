import MySQLdb
import crawler
import xml.dom.minidom
import time

def GetFeedInfo(url):
    c = crawler.Crawler('')
    rss = c.download(url)
    dom = xml.dom.minidom.parseString(str.strip(rss))
    items = dom.getElementsByTagName('item')
    title = ''
    link = ''
    description = ''
    for item in items:
        title = item.getElementsByTagName('title')[0].firstChild.data
        link = item.getElementsByTagName('link')[0].firstChild.data
        description = item.getElementsByTagName('description')[0].firstChild.data
        break
    return [title, link, description]
        

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "reader")
cursor = connection.cursor()
data = open("feed_popularity.txt")

try:
    n = 0
    for line in data:
        [feed, title, popularity] = line.split('\t')
        [article_title, article_link, description] = GetFeedInfo(feed)
        print feed, article_title, article_link
        cursor.execute("replace into feeds(name, link, popularity,latest_article_title,latest_article_link,modify_at) values (%s,%s,%s,%s,%s,%s);",
                       (title, feed, popularity, article_title, article_link, int(time.mktime(time.localtime()))))
        
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
