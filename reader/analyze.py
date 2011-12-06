# -*- coding: utf-8 -*-
import crawler
import xml.dom.minidom
import urllib
import codecs
import time
import os
import math
from operator import itemgetter

def GetFeedLink(url):
    p = url.find('tag:google.com,2005:reader/feed/http://')
    if p < 0:
        return ''
    p += len('tag:google.com,2005:reader/feed/')
    return url[p:]

def ProcessUser(user_xml, link_feed_map, user_like_links, user_share_links, feed_title_map, link_cats):
    dom = xml.dom.minidom.parseString("<?xml version=\"1.0\"?><feed xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:gr=\"http://www.google.com/schemas/reader/atom/\" xmlns:idx=\"urn:atom-extension:indexing\" xmlns=\"http://www.w3.org/2005/Atom\" idx:index=\"no\" gr:dir=\"ltr\">"
                                      + user_xml + '</feed>')
    user = dom.getElementsByTagName('user')[0].getAttribute('id')
    if user not in user_share_links:
        user_share_links[user] = set()
    entries = dom.getElementsByTagName('entry')
    for entry in entries:
        link = entry.getElementsByTagName('link')[0].getAttribute('href')
        user_share_links[user].add(link)
        
        if link not in link_cats:
            link_cats[link] = set()
            
        feed_link = GetFeedLink(entry.getElementsByTagName('source')[0].getElementsByTagName('id')[0].firstChild.data)
        if len(feed_link) == 0:
            continue
        if link not in link_feed_map:
            link_feed_map[link] = feed_link
        feed_title = entry.getElementsByTagName('source')[0].getElementsByTagName('title')[0].firstChild.data
        if feed_link not in feed_title_map:
            feed_title_map[feed_link] = feed_title
        likers = entry.getElementsByTagName('liker')
        for liker in likers:
            liker_id = liker.firstChild.data
            if liker_id not in user_like_links:
                user_like_links[liker_id] = set()
            user_like_links[liker_id].add(link)
        cats = entry.getElementsByTagName('category')
        for cat in cats:
            cat_name = cat.getAttribute('term')
            link_cats[link].add(cat_name)

def Process():
    link_feed_map = dict()
    user_like_links = dict()
    user_share_links = dict()
    feed_title_map = dict()
    link_cats = dict()
    
    sr = open('googlereader.txt','r')
    feed_users = dict()
    user_xml = ''
    for line in sr:
        if line.find('</user>') >= 0:
            user_xml += line
            ProcessUser(user_xml, link_feed_map, user_like_links, user_share_links, feed_title_map, link_cats)
            user_xml = ''
        else:
            user_xml += line
    sr.close()
    feed_pop = dict()
    sw = codecs.open('user_feeds.txt', 'w','utf-8')
    for user, links in user_like_links.items():
        rank = dict()
        for link in links:
            feed = link_feed_map[link]
            if feed not in rank:
                rank[feed] = 0
            rank[feed] += 1
        for feed, w in rank.items():
            sw.write(user + '\t' + feed + '\t' + str(w) + '\n')
    sw.close()

    feed_cats = dict()
    for link, cats in link_cats.items():
        if link not in link_feed_map:
            continue
        feed = link_feed_map[link]
        if feed not in feed_cats:
            feed_cats[feed] = dict()
        for cat in cats:
            if cat not in feed_cats[feed]:
                feed_cats[feed][cat] = 0
            feed_cats[feed][cat] += 1
    sw = codecs.open('feed_cats.txt', 'w','utf-8')
    for feed,cats in feed_cats.items():
        sw.write(feed + '||')
        for cat in cats:
            sw.write(cat + '||')
        sw.write('\n')
    sw.close()
    return feed_title_map


def FeedSimilarityByCF(feed_title_map):
    sr = open('user_feeds.txt', 'r')
    ni = dict()
    user_items = dict()
    feed = ''
    #load data
    for line in sr:
        line = str.strip(line)
        [user, feed, w] = line.split('\t')
        if user not in user_items:
            user_items[user] = dict()
        user_items[user][feed] = int(w)
        
    #calc sim table
    sim = dict()
    for user, feeds in user_items.items():
        idf = 1 / math.log(2 + 1.0 * len(feeds))
        for i, wi in feeds.items():
            if i not in sim:
                sim[i] = dict()
                ni[i] = 0
            ni[i] += 1
            for j, wj in feeds.items():
                if i == j:
                    continue
                if j not in sim[i]:
                    sim[i][j] = 0
                sim[i][j] += idf
    sw = codecs.open('feed_popularity.txt', 'w', 'utf-8')
    for feed, pop in sorted(ni.items(), key=itemgetter(1), reverse=True):
        sw.write(feed + '\t' + feed_title_map[feed] + '\t' + str(pop) + '\n')
    sw.close()
    sw = codecs.open('feed_similarity.txt','w', 'utf-8')
    for i, rel_items in sim.items():
        rank = dict()
        if ni[i] < 10:
            continue
        for j, wij in rel_items.items():
            if ni[j] < 10:
                continue
            if ni[j] > ni[i] * 2:
                continue
            rank[j] = wij / (1 + math.sqrt(ni[i] * ni[j]))
        for j, wij in sorted(rank.items(), key=itemgetter(1), reverse=True)[0:20]:
            sw.write(i + '\t' + j + '\t' + str(wij) + '\n')
    sw.close()

feed_title_map = Process()
FeedSimilarityByCF(feed_title_map)
