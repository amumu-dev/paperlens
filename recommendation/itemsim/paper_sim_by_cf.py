import MySQLdb
import sys
sys.path.append("../../include/python/")
from paper import Paper
import math
from operator import itemgetter

def paperSim():
    connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor = connection.cursor()
    simTable = dict()
    ni = dict()
    cursor.execute("select user_id,paper_id from user_paper_behavior where user_id >0 order by user_id;;")

    numrows = int(cursor.rowcount)
    print numrows

    prev_entity = -1
    papers = set()
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor.fetchone()
        entity_id = row[1]
        paper_id = row[0]
        if prev_entity != entity_id:
            if len(papers) < 500:
                for i in papers:
                    if i not in simTable:
                        simTable[i] = dict()
                        ni[i] = 0
                    ni[i] = ni[i] + 1
                    for j in papers:
                        if i == j:
                            continue
                        if j not in simTable[i]:
                            simTable[i][j] = 0
                        weight = 1 / math.log(2 + len(papers))
                        simTable[i][j] = simTable[i][j] + weight
            prev_entity = entity_id
            papers = set()
        papers.append(paper_id)
    print len(simTable)

    cursor.execute("truncate table papersim_cf;")
    n = 0
    for i, rels in simTable.items():
        n = n + 1
        if n % 10000 == 0:
            print n
        k = 0
        for j, weight in sorted(rels.items(), key=itemgetter(1), reverse=True):
            cursor.execute("replace into papersim_cf(src_id,dst_id,weight) values (%s,%s,%s);",(i, j, weight / math.sqrt(1 + ni[i] * ni[j])))
            k = k + 1
            if k > 20:
                break

    connection.commit()
    cursor.close()
    connection.close()

paperSim()
