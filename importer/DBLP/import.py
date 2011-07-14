import xml.dom.minidom

def DecodeItem(item):
    print item
    dom = xml.dom.minidom.parseString(item)
    for node in dom.documentElement.childNodes:
        print node.nodeValue
    
datafile = open("D:\\book\\data\\dblp.xml")
item = ''
n = 0
for line in datafile:
    if line.find('<incollection') >= 0:
        item = ''
        n = n + 1
    if line.find('</incollection>') >= 0:
        item = item + line
        DecodeItem(item)
        item = ''
    item = item + line
    if n > 5:
        break



