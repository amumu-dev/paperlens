import md5

class Paper:

    def __init__(self):
        self.title = ''
        self.publish_year = 0
        self.authors = []
        self.type = ''
        self.abstract = ''
        self.conference = ''

    def printData(self):
        info = self.title + ', '
        for author in self.authors:
            info = info + author + ', '
        info = info + str(self.publish_year) + ' ' + self.hashCode()
        print info

    def hashCode(self):
        return md5.new(self.title.lower() + self.authors[0][0:1] + str(self.publish_year)).hexdigest()
