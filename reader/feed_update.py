import MySQLdb
import crawler
import xml.dom.minidom
import time
import random

def GetDate(pub_date):
    tks = []
    try:
        tks = unicode.split(pub_date, ' ')
    except TypeError, e:
        tks = str.split(pub_date, ' ')
    if len(tks) < 5:
        return 0
    buf = ''
    for i in range(0,5):
        buf += tks[i] + ' '
    try:
        ret = int(time.mktime(time.strptime(unicode.strip(buf), '%a, %d %b %Y %H:%M:%S')))
        return ret
    except:
        return 0


def GetFeedInfo(url):
    c = crawler.Crawler('')
    rss = c.download(url)
    ret = []
    if len(rss) < 20:
        return ret
    try:
        dom = xml.dom.minidom.parseString(str.strip(rss))
        items = dom.getElementsByTagName('item')
        title = ''
        link = ''
        pub_date = ''
        for item in items:
            title_node = item.getElementsByTagName('title')
            if len(title_node) > 0:
                title = title_node[0].firstChild.data
            link_node = item.getElementsByTagName('link')
            if len(link_node) > 0:
                link = link_node[0].firstChild.data
            date_node = item.getElementsByTagName('pubDate')
            if len(date_node) > 0:
                pub_date = date_node[0].firstChild.data
            pdate = GetDate(pub_date)
            itemxml = item.toxml()
            if pdate > 0 and len(itemxml) > 200 and len(itemxml) < 2000:
                ret.append([title, link, pdate, itemxml])
        return ret
    except xml.parsers.expat.ExpatError, e:
        return ret
    except AttributeError, e:
        return ret
        
def InsertArticle(article, cursor):
    if len(article) != 4:
        return -1
    [title, link, pdate, xml] = article
    cursor.execute("select id from articles where link=%s;", (link))
    numrows = int(cursor.rowcount)
    if numrows <= 0:
        cursor.execute("insert into articles(title, link, pub_at, content) values (%s, %s, %s, %s);",
                       (title, link, pdate, xml))
        cursor.execute("select id from articles where link=%s;", (link))
        numrows = int(cursor.rowcount)
    if numrows <= 0:
        return -1
    row = cursor.fetchone()
    return int(row[0])

def GetFeedId(link, cursor):
    cursor.execute("select id from feeds where link=%s;", (link))
    numrows = int(cursor.rowcount)
    if numrows <= 0:
        return -1
    row = cursor.fetchone()
    return int(row[0])

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "reader", charset="utf8")
cursor = connection.cursor()
cursor.execute("set names utf8")


data = open("feed_popularity.txt")
for line in data:
    [feed, title, popularity] = line.split('\t')
    cursor.execute("insert into feeds(name, link, popularity) values (%s,%s,%s) on duplicate key update popularity=values(popularity);",
                   (title, feed, popularity))
data.close()

now_timestamp = int(time.mktime(time.localtime()))

link_id_map = dict()
cursor.execute("select id,link from feeds;")
numrows = int(cursor.rowcount)
for k in range(numrows):
    row = cursor.fetchone()
    link_id_map[row[1]] = row[0]

cursor.execute("truncate table feedsim;")
data = open("feed_similarity.txt")
n = 0
for line in data:
    try:
        n += 1
        [src_feed, dst_feed, weight] = line.split('\t')
        if src_feed not in link_id_map or dst_feed not in link_id_map:
            continue
        if n % 10000 == 0:
            print n, link_id_map[src_feed], link_id_map[dst_feed], weight
        cursor.execute("replace into feedsim(src_id, dst_id, weight) values (%s,%s,%s);",
                       (link_id_map[src_feed], link_id_map[dst_feed], float(weight)))
    except:
        continue

n = 0
data = open("feed_popularity.txt")
for line in data:
    if random.random() > 0.3:
        continue
    [feed, title, popularity] = line.split('\t')
    feed_id = GetFeedId(feed, cursor)
    if feed_id < 0:
        continue
    articles = GetFeedInfo(feed)
    print feed, title, len(articles)
    for article in articles:
        article_id = InsertArticle(article, cursor)
        [atitle, alink, apdate, axml] = article
        if article_id < 0 or (now_timestamp - apdate) > 24 * 3600 * 10:
            continue
        cursor.execute("replace into feed_articles(feed_id, article_id) values (%s, %s)", (feed_id, article_id))

connection.commit()
cursor.close()
connection.close()
data.close()
