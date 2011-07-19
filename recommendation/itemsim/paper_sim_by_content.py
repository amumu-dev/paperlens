import MySQLdb
import sys

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()

simTable = dict()


cursor.execute("select paper_id,author_id from paper_author where rank<2 order by author_id;")

numrows = int(cursor.rowcount)
print numrows

prev_author = -1
papers = []
for k in range(numrows):
    row = cursor.fetchone()
    author_id = row[1]
    paper_id = row[0]
    if prev_author != author_id:
        if len(papers) < 50:
            for i in papers:
                if i not in simTable:
                    simTable[i] = dict()
                if i % 5 != paper_mod:
                    continue
                for j in papers:
                    if i == j:
                        continue
                    if j not in simTable[i]:
                        simTable[i][j] = 0
                    simTable[i][j] = simTable[i][j] + 1
        prev_author = author_id
        papers = []
    papers.append(paper_id)
print len(simTable)

for i, rels in simTable.items():
    for j, weight in rels.items():
        cursor.execute("insert into papersim_author(src_id,dst_id,weight) values (%s,%s,%s);",(i, j, weight))

connection.commit()
cursor.close()
connection.close()


