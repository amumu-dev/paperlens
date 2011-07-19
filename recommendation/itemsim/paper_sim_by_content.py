import MySQLdb
import sys
sys.path.append("../../include/python/")
from paper import Paper
import math


def getPaperInfo():
    ret = dict()
    connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor = connection.cursor()
    cursor.execute("select id,year,booktitle from paper where length(booktitle) > 0 and year > 0 and type = %s;", ("inproceedings"))
    numrows = int(cursor.rowcount)
    for k in range(numrows):
        row = cursor.fetchone()
        paper_id = row[0]
        year = row[1]
        booktitle = row[2]
        ret[paper_id] = (year, booktitle)
    cursor.close()
    connection.close()
    return ret
    

def authorBasedPaperSim():
    paper_info = getPaperInfo()
    print len(paper_info)
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
        if paper_id not in paper_info:
            continue
        if prev_author != author_id:
            if len(papers) < 50:
                for i in papers:
                    if i not in simTable:
                        simTable[i] = dict()
                    (year_i, booktitle_i) = paper_info[i]
                    for j in papers:
                        if i == j:
                            continue
                        (year_j, booktitle_j) = paper_info[i]
                        if abs(year_j - year_i) > 10:
                            continue
                        if j not in simTable[i]:
                            simTable[i][j] = 0
                        weight = 1 / math.log(2 + len(papers))
                        if booktitle_i == booktitle_j:
                            weight = weight * 2
                        weight = weight / (1 + 0.1 * abs(year_j - year_i))
                        simTable[i][j] = simTable[i][j] + weight
            prev_author = author_id
            papers = []
        papers.append(paper_id)
    print len(simTable)

    for i, rels in simTable.items():
        for j, weight in rels.items():
            cursor.execute("replace into papersim_author(src_id,dst_id,weight) values (%s,%s,%s);",(i, j, weight))

    connection.commit()
    cursor.close()
    connection.close()

authorBasedPaperSim()
