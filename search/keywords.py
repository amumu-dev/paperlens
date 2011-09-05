#ArNet
import MySQLdb
import re
import sys
import math
from operator import itemgetter
sys.path.append("../importer/")
import crawler

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()


try:
    cursor.execute("select title from paper")

    numrows = int(cursor.rowcount)
    print numrows

    keywords = dict()
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor.fetchone()
        title = row[0].lower()
        words = re.split('\W+', title)
        for word in words:
            if word not in keywords:
                keywords[word] = 1
            else:
                keywords[word] = keywords[word] + 1

    connection.commit()
    cursor.close()
    connection.close()

    fp = open("keywords.txt", "w")
    for word, weight in sorted(keywords.items(), key=itemgetter(1), reverse=True):
        fp.write(word + "\t" + str(weight))
    fp.close()
    
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
