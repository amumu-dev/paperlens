#ArNet
import MySQLdb
import sys
sys.path.append("../")
import paperlens_import

def Extract(buf):
    p1 = buf.find('>')
    key = ''
    value = ''
    p1 = p1 + 1
    p2 = buf.find('<', p1)
    key = buf[0:p1].strip()
    value = buf[p1:p2].strip()
    return (key, value)

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()


try:
    data = open("../../../data/citeseer.txt")
    cursor.execute("truncate table paper_citeseer")
    cursor.execute("truncate table cite_citeseer")
    citeseer_id_map = dict()
    title = ''
    citeseer_id = ''
    n = 0
    for line in data:
        (key, value) = Extract(line)
        if line.find("<record>") >= 0:
            if len(title) > 20:
                hashvalue = paperlens_import.intHash(title.lower())
                cursor.execute("select count(*),id from paper where hashvalue=%s",(hashvalue))
                row = cursor.fetchone()
                if int(row[0]) == 1:
                    paper_id = int(row[1])
                    if citeseer_id not in citeseer_id_map:
                        citeseer_id_map[citeseer_id] = paper_id
                    #cursor.execute("replace into paper_citeseer (paper_id, citeseer_key) values (%s, %s)",(paper_id, citeseer_id))

                    if n % 10000 == 0:
                        print n, title, citeseer_id
                        n = n + 1

            title = ''
            citeseer_id = ''
        if key == "<dc:title>":
            title = value
        if key == "<identifier>":
            citeseer_id_tks = value.split(":")
            citeseer_id = citeseer_id_tks[2]
    data.close()
    
    data = open("../../../data/citeseer.txt")
    citeseer_id = ''
    citations = set()
    n = 0
    for line in data:
        (key, value) = Extract(line)
        if line.find("<record>") >= 0:
            if citeseer_id not in citeseer_id_map:
                continue
            paper_id = citeseer_id_map[citeseer_id]
            for dst_key in citations:
                if dst_key not in citeseer_id_map:
                    continue
                dst_id = citeseer_id_map[dst_key]
                cursor.execute("replace into cite_citeseer(src_id, dst_id, weight) values (%s, %s, %s)", (citeseer_id, dst_key, 1))

                if n % 10000 == 0:
                    print n, title, citeseer_id
                    n = n + 1

            citeseer_id = ''
            citations = set()
        if key == "<identifier>":
            citeseer_id_tks = value.split(":")
            citeseer_id = citeseer_id_tks[2]
        if key == "<dc:relation>":
            citations.add(value)
            
    data.close()  
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
