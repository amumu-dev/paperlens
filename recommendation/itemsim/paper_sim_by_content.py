import MySQLdb
import sys

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
cursor.execute("select paper_id,author_id from paper_author;")

numrows = int(cursor.rowcount)
print numrows
author_papers = dict()

n = 0
for i in range(numrows):
    n = n + 1
    print n
    row = cursor.fetchone()
    author_id = row[1]
    paper_id = row[1]
    if author_id not in author_papers:
        author_papers[author_id] = []
    author_papers[author_id].append(paper_id)


