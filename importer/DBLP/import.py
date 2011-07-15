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

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()

data = open("../../../data/dblp.xml")

item = Paper()
paper_types = set(['article','inproceedings','proceedings','book','incollection','phdthesis','mastersthesis','www']);

try:
    n = 0
    for line in data:
        endTag = ExtractEndTag(line);
        if endTag in paper_types:
            cursor.execute("insert into paper(title,year,booktitle,type) values (%s,%s,%s,%s);", (item.title, item.publish_year, item.booktitle, endTag))
            n = n + 1
            if n % 1000 == 0:
                print str(n)
            item = Paper()
        else:
            [key,value] = Extrack(line)
            if key == "<author>":
                item.authors.append(value)
            elif key == "<title>":
                item.title = value.strip('.')
            elif key == "<year>":
                item.publish_year = int(value)
            elif key == "<booktitle>":
                item.booktitle = value
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
