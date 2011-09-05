#ArNet
import MySQLdb
import re
import sys
sys.path.append("../importer/")
import crawler

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()


try:
    data = open("../../data/enwiki-20110722-all-titles-in-ns0")
    c = crawler.Crawler("")
    for line in data:
        line = line.replace("-", " ")
        line = line.replace("_", " ")
        line = line.strip()
        line = line.lower()
        if len(line) < 5 or len(line) > 48:
            continue
        matchobj = re.match('[a-z]+[a-z0-9 ]+', line)
        if matchobj == None:
            continue
        if matchobj.group(0) == line:
            print line
    data.close()
    
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
