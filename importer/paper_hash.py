#ArNet
import MySQLdb
import sys
sys.path.append("../")
sys.path.append("./")
import hashlib
from paper import Paper
import paperlens_import

connection = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()

try:
    paper_hash = dict()
    cursor.execute("select id,hashvalue from paper")
    n = 0
    while 1:
        row = cursor.fetchone()
        if row == None:
            break
        paper_id = int(row[0])
        title = row[1]
        paper_hash[paper_id] = paperlens_import.intHash(title.lower())
        n = n + 1
        if n % 10000 == 0:
            print str(n)

    n = 0
    for (paper_id, hash_value) in paper_hash.items():
        cursor.execute("update paper set hashvalue=%s where id=%s",(hash_value,paper_id))
        n = n + 1
        if n % 10000 == 0:
            print str(n)
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
