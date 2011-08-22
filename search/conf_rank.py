import MySQLdb

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()


try:
    booktitle_citations = dict()
    booktitle_count = dict()
    papers = dict()
    cursor.execute("select id,booktitle,citations from paper")
    k = 0
    while 1:
        row = cursor.fetchone()
        if row == None:
            break
        papers[int(row[0])] = row[1]
        booktitle = row[1]
        citations = int(row[2])
        if booktitle not in booktitle_count:
            booktitle_citations[booktitle] = citations
            booktitle_count[booktitle] = 1
        else:
            booktitle_citations[booktitle] = booktitle_citations[booktitle] + citations
            booktitle_count[booktitle] = booktitle_count[booktitle] + 1
        k = k + 1
        if k % 100000 == 0:
            print k
    k = 0
    for (paper_id, booktitle) in data:
        rank = float(booktitle_citations[booktitle]) / float(booktitle_count[booktitle] + 10)
        cursor.execute("update paper set rank=%s where id=%s", (rank, paper_id))
        k = k + 1
        if k % 100000 == 0:
            print k
        
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
