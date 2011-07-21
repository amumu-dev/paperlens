#ArNet

import MySQLdb
import sys
sys.path.append("../")
import hashlib
from paper import Paper

def intHash(buf):
    ret = 0
    for i in range(len(buf)):
        ret = ret * 31 + ord(buf[i])
    return ret % 200000000

connection = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()

paper_hash = dict()
try:
    cursor.execute("select id,title from paper limit 100")
    n = 0
    while 1:
        row = cursor.fetchone()
        if row == None:
            break
        paper_id = int(row[0])
        title = row[1]
        paper_hash[paper_id] = intHash(title.lower())
        n = n + 1
        if n % 10000 == 0:
            print str(n)

    for (paper_id, hash_value) in paper_hash.items():
        cursor.execute("update paper set hashvalue=%s where id=%s",(hash_value,paper_id))
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
