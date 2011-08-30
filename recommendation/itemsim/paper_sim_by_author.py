import MySQLdb
import sys
sys.path.append("../../include/python/")
from paper import Paper
import math
from operator import itemgetter


connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
cursor.execute("truncate table papersim_author;")

for year in range(1960, 2012):
    print year
    simTable = dict()
    cursor.execute("select paper_author.paper_id, paper_author.author_id, paper.year from paper_author, paper where paper.id = paper_author.paper_id and paper.year>%s and paper.year<%s order by author_id;", (year - 3, year + 3))

    numrows = int(cursor.rowcount)
    print numrows

    prev_entity = -1
    papers = dict()
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor.fetchone()
        entity_id = row[1]
        paper_id = row[0]
        paper_year = row[2]
        if prev_entity != entity_id:
            if len(papers) < 100:
                for (i, yi) in papers:
                    if yi != year:
                        continue
                    if i not in simTable:
                        simTable[i] = dict()
                    for j in papers:
                        if i == j:
                            continue
                        if j not in simTable[i]:
                            simTable[i][j] = 0
                        weight = 1 / math.log(2 + len(papers))
                        simTable[i][j] = simTable[i][j] + weight
            prev_entity = entity_id
            papers = []
        papers[paper_id] = paper_year
    print len(simTable)

    
    n = 0
    for i, rels in simTable.items():
        n = n + 1
        if n % 10000 == 0:
            print n
        k = 0
        for j, weight in sorted(rels.items(), key=itemgetter(1), reverse=True):
            cursor.execute("replace into papersim_author(src_id,dst_id,weight) values (%s,%s,%s);",(i, j, weight))
            k = k + 1
            if k > 10:
                break

connection.commit()
cursor.close()
connection.close()

