import MySQLdb
import sys
sys.path.append("../")
from paper import Paper

def Extrack(buf):
    p1 = buf.find('>')
    key = ''
    value = ''
    p1 = p1 + 1
    p2 = buf.find('<', p1)
    key = buf[0:p1]
    value = buf[p1:p2]
    return [key,value]

def ExtractEndTag(buf):
    p1 = buf.find('</') + 2
    p2 = buf.find('>',p1)
    return buf[p1:p2]

def ExtractStartTag(buf):
    p1 = buf.find('<') + 1
    p2 = buf.find(' ',p1)
    return buf[p1:p2]

def ExtractDBLPKey(buf):
    if buf.find('mdate=') < 0:
        return ''
    p1 = buf.find('key=\"') + len('key=\"')
    p2 = buf.find('\"', p1)
    return buf[p1:p2]

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
cursor.execute("truncate table paper;")
connection.commit()
data = open("../../../data/dblp.xml")

dblp_key_map = dict()
dblp_key = ''
try:
    cursor.execute("select id,dblp_key from paper limit 10000")
    n = 0
    while 1:
        row = cursor.fetchone()
        if row == None:
            break
        dblp_key_map[row[1]] = (int)(row[0])
        print row[1],row[0]
        n = n + 1
        if n % 10000 == 0:
            print str(n)
        if n > 10000:
            break
    print 'ok'
    n = 0
    for line in data:
        dblp_key_tmp = ExtractDBLPKey(line)
        if len(dblp_key_tmp) > 0:
            dblp_key = dblp_key_tmp
        if dblp_key not in dblp_key_map:
            continue
        src_id = dblp_key_map[dblp_key]
        
        [key,value] = Extrack(line)
        if key == "<cite>":
            if value not in dblp_key_map:
                continue
            dst_id = dblp_key_map[value]
            cursor.execute("insert into cite(src_id,dst_id) values (%s,%s);",
                           (src_id,dst_id))
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
