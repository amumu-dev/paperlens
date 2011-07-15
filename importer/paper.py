import hashlib

class Paper:

    def __init__(self):
        self.title = ''
        self.publish_year = 0
        self.authors = []
        self.type = ''
        self.abstract = ''
        self.booktitle = ''
        self.dblp_key = ''

    def printData(self):
        if len(self.authors) > 0:
            info = self.title + ', '
            for author in self.authors:
                info = info + author + ', '
            info = info + str(self.publish_year) + ' ' + self.hashCode()
            print info

    def hashCode(self):
        return hashlib.md5(self.title.lower() + self.authors[0][0:1] + str(self.publish_year)).hexdigest()
