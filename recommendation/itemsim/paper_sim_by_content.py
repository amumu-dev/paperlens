import MySQLdb
import sys
sys.path.append("../../include/python/")
from paper import Paper
import math
from operator import itemgetter

def generatePaperEntities():
    connection1 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor1 = connection1.cursor()
    connection2 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor2 = connection1.cursor()
    cursor2.execute("truncate table tmp_paper_entities;")
    cursor1.execute("select paper.id, paper_author.author_id, paper.year, paper.title, paper.booktitle from paper_author left join paper on paper_author.paper_id = paper.id")
    entity_dict = dict()
    numrows = int(cursor1.rowcount)
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor.fetchone()
        paper_id = row[0]
        author_id = row[1]
        year = row[2]
        entities = row[3].lower().split()
        entities.append("a:" + str(author_id))
        booktitle = row[4]
        entities.append(booktitle)
        for entity in entities:
            entity_id = len(entity_dict)
            if entity in entity_dict:
                entity_id = entity_dict[entity]
            else:
                entity_dict[entity] = entity_id
            cursor2.execute("replace into tmp_paper_entities (paper_id, entity_id) values (%s, %s)", (paper_id,entity_id))
    cursor1.close()
    connection1.close()
    cursor2.close()
    connection2.close()
    

def paperSim():
    connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor = connection.cursor()

    simTable = dict()
    cursor.execute("select paper_id,entity_id from tmp_paper_entities order by entity_id;")

    numrows = int(cursor.rowcount)
    print numrows

    prev_entity = -1
    papers = []
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor.fetchone()
        entity_id = row[1]
        paper_id = row[0]
        if paper_id not in paper_info:
            continue
        if prev_entity != entity_id:
            if len(papers) < 100:
                for i in papers:
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
        papers.append(paper_id)
    print len(simTable)

    cursor.execute("truncate table papersim_author;")
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

generatePaperEntities()
paperSim()
