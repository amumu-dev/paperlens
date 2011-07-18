import MySQLdb
import sys

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()

simTable = dict()

for mod in range(30):
    cursor.execute("select paper_id,author_id from paper_author where author_id%30=" + str(mod) + " order by author_id;")

    numrows = int(cursor.rowcount)
    print mod, numrows

    prev_author = -1
    papers = []
    for k in range(numrows):
        row = cursor.fetchone()
        author_id = row[1]
        paper_id = row[0]
        if prev_author != author_id:
            for i in papers:
                if i not in simTable:
                    simTable[i] = dict()
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
                    


