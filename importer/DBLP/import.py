import _mysql
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

connection = _mysql.connect('localhost', 'paperlens', 'paper1ens', 'paperlens')

data = open("../../../data/dblp.xml")

item = Paper()

for line in data:
    if line.find('<incollection') >= 0:
        item = Paper()
    elif line.find('</incollection>') >= 0:
        item.printData()
        item = Paper()
    else:
        [key,value] = Extrack(line)
        if key == "<author>":
            item.authors.append(value)
        elif key == "<title>":
            item.title = value
        elif key == "<year>":
            item.publish_year = int(value)
