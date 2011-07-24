#ArNet
import MySQLdb
import sys
sys.path.append("../")
from paper import Paper
import paperlens_import
import datetime

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()

try:
    n = 0
    author_rank = dict()
    now = datetime.datetime.now()
    now_year = now.year
    cursor.execute("select paper.citations,author.id,paper_author.rank,paper.year from paper,paper_author,author where paper.id=paper_author.paper_id and paper_author.author_id=author.id")
    while 1:
        row = cursor.fetchone()
        if row == None:
            break
        author = int(row[1])
        citations = int(row[0])
        rank = int(row[2])
        year = int(row[3])
        if author not in author_rank:
            author_rank[author] = 0.0
        author_rank[author] += citations / ((1 + rank) * (1 + now_year - year))
        n = n + 1
        if n % 10000 == 0:
            print n
    n = 0
    for (author, rank) in author_rank:
        cursor.execute("insert into author_rank (author_id, weight) values (%s, %s)", (author_id, rank))
        n = n + 1
        if n % 10000 == 0:
            print n
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
