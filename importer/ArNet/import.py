#ArNet
import MySQLdb
import sys
sys.path.append("../")
from paper import Paper
import paperlens_import

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()
data = open("../../../data/DBLP-citation.txt")

try:
    title = ''
    citations = 0
    n = 0
    for line in data:
        if line.find("#*") == 0:
            title = line[2:len(line)-1]
        if line.find("#citation") == 0:
            citations = int(line[9:])
        print title, citations
        n = n + 1
        if n > 100:
            break
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
