import MySQLdb
import sys
sys.path.append("../../include/python/")
from paper import Paper
import math
from operator import itemgetter

def getWordFreq():
    connection1 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor1 = connection1.cursor()
    cursor1.execute("select id, title, abstract from paper where length(abstract)>50")
    ret = dict()
    numrows = int(cursor1.rowcount)
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor1.fetchone()
        paper_id = row[0]
        entities = dict()
        words = (row[1] + " " + row[2].lower()).split()
        for word in words:
            if word not in ret:
                ret[word] = 1
            else:
                ret[word] = ret[word] + 1
    cursor1.close()
    connection1.close()
    return ret

def generatePaperEntities():
    word_freq = getWordFreq()
    connection1 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor1 = connection1.cursor()
    connection2 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor2 = connection1.cursor()
    cursor2.execute("truncate table tmp_paper_entities;")
    #cursor1.execute("select paper.id, paper_author.author_id, paper.year, paper.title, paper.booktitle from paper_author left join paper on paper_author.paper_id = paper.id")
    cursor1.execute("select id, title, abstract from paper where length(abstract)>50")
    entity_dict = dict()
    numrows = int(cursor1.rowcount)
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor1.fetchone()
        paper_id = row[0]
        entities = dict()
        words = (row[1] + " " + row[2].lower()).split()
        for word in words:
            if word not in word_freq:
                continue
            if word_freq[word] > 200:
                continue
            if word not in entities:
                entities[word] = 1
            else:
                entities[word] = entities[word] + 1
        for (entity,weight) in entities.items():
            entity_id = len(entity_dict)
            if entity in entity_dict:
                entity_id = entity_dict[entity]
            else:
                entity_dict[entity] = entity_id
            cursor2.execute("replace into tmp_paper_entities (paper_id, entity_id, weight) values (%s, %s, %s)", (paper_id,entity_id,weight))
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
            if len(papers) < 200:
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
