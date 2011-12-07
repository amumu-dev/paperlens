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
    if len(rss) < 20:
        return ['', '', 0]
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
            break
        pdate = GetDate(pub_date)
        return [title, link, pdate]
    except xml.parsers.expat.ExpatError, e:
        return ['', '', 0]
    except AttributeError, e:
        return ['', '', 0]
        

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "reader", charset="utf8")
cursor = connection.cursor()
cursor.execute("set names utf8")


data = open("feed_popularity.txt")
for line in data:
    [feed, title, popularity] = line.split('\t')
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

link_id_map = dict()
cursor.execute("select id,link from feeds;")
numrows = int(cursor.rowcount)
for k in range(numrows):
    row = cursor.fetchone()
    link_id_map[row[1]] = row[0]
print 'new feed number : ', len(feeds)

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
                       (link_id_map[src_feed], link_id_map[dst_feed], weight))
    except:
        continue

n = 0
data = open("feed_popularity.txt")
for line in data:
    [feed, title, popularity] = line.split('\t')
    if feed in feeds:
        print 'up to date', feed
        continue
    [article_title, article_link, pub_date] = GetFeedInfo(feed)
    if len(article_title) == 0:
        continue
    print feed, article_title, article_link, pub_date
    cursor.execute("select modify_at from feeds where link=%s", (feed));
    numrows = int(cursor.rowcount)
    last_modify_at = 0
    for k in range(numrows):
        row = cursor.fetchone()
        last_modify_at = int(row[0])
    if pub_date - last_modify_at > 3600 * 24:
        print "un updated for ", float(pub_date - last_modify_at) / (3600.0 * 24.0), " days"
        if random.random() > 0.3:
            continue
    if pub_date - last_modify_at < 3600:
        print "updated in one hour"
        continue
    cursor.execute("insert into feeds(link, latest_article_title,latest_article_link,modify_at) values (%s,%s,%s,%s) on duplicate key update latest_article_title=values(latest_article_title),modify_at=values(modify_at),latest_article_link=values(latest_article_link);", (feed, article_title, article_link, pub_date))

connection.commit()
cursor.close()
connection.close()
data.close()
