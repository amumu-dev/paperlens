__author__ = 'xlvector'

from Tkinter import *
import random
import math
from operator import itemgetter


def RDT(points,wx,hy, max_depth):
    splits = []
    for k in range(0,max_depth):
        axis = random.randint(0,0)
        sv = random.random()
        splits.append([axis, sv])
    root = [0, 0, -1, -1, 0, points]
    tree = [root]
    Q = [root]
    k = 0
    while len(Q) > 0:
        [depth, index, left, right, predict, ps] = Q.pop()
        if len(ps) == 0 and predict != 0:
            print index
        if depth >= max_depth or len(ps) < 20:
            continue
        [axis,sv] = splits[depth]
        pleft = dict()
        pright = dict()
        p1 = 0
        p2 = 0
        for p, c in ps.items():
            f = [float(int(p/1000))/(wx*1.0), float(int(p%1000))/(hy*1.0)]
            if f[axis] < sv:
                pleft[p] = c
                p1 += c
            else:
                pright[p] = c
                p2 += c
        tree[index][5] = dict()

        if len(pleft) > 0:
            p1 = p1 / (len(pleft) * 1.0)
        tree[index][2] = len(tree)
        leftNode = [depth+1, len(tree), -1, -1, p1, pleft]
        tree.append(leftNode)
        Q.append(leftNode)

        if len(pright) > 0:
            p2 = p2 / (len(pright) * 1.0)
        tree[index][3] = len(tree)
        rightNode = [depth+1, len(tree), -1, -1, p2, pright]
        tree.append(rightNode)
        Q.append(rightNode)
    return [tree, splits]

master = Tk()

W = 600
H = 500

w = Canvas(master, width=W, height=H, bg="white")
w.pack(fill=BOTH)

points = dict()
train = dict()
random.seed(198571)
def getHash(x, y):
    return x * 1000 + y


for k in range(200):
    x = int(random.uniform(0, W))
    y = 0
    points[getHash(x,y)] = H - int(0.9 * x * (0.5 + 0.5 * math.sin(0.05 * x))) + random.gauss(10,20)

random.seed(198571)
pb = dict()
nn = dict()
for tree in range(0,100):
    [tree, splits] = RDT(points,W,H,128)
    for i in range(0,W):
        for j in range(0,1):
            f = [i / (W*1.0),j / (H*1.0)]
            k = 0
            pk = 0
            while True:
                [depth, index, left, right, predict, ps] = tree[k]
                if left < 0 and right < 0:
                    pk = predict
                    break
                [axis, sv] = splits[depth]
                if f[axis] < sv:
                    if left < 0:
                        pk = predict
                        break
                    else:
                        k = left
                else:
                    if right < 0:
                        pk = predict
                        break
                    else:
                        k = right
            h = getHash(i, j)
            if h not in nn:
                nn[h] = 0
                pb[h] = 0
            nn[h] += 1
            pb[h] += pk
            
for p,c in pb.items():
    px = int(p / 1000)
    py = int(pb[p] / nn[p])
    w.create_oval(px - 2,py-2, px+2,py+2, fill="black",outline="black")

for p,c in points.items():
    px = int(p / 1000)
    py = int(c)
    w.create_oval(px - 2,py-2, px+2,py+2, fill="red",outline="red")
        
mainloop()