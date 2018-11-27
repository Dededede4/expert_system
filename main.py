class Node(object): # Need to be true
	def __init__(self):
		self.letter = None
		self.BRACKET = None
		self.NOT = False
		self.AND = None
		self.OR = None
		self.XOR = None

	def display(self, ignoreBracket=False):
		if (self.NOT):
			print("!", end="")

		if (self.letter):
			print(self.letter, end="")

		if (ignoreBracket == False and self.BRACKET):
			print('( ', end="")
			self.BRACKET.BRACKET.display()
			print(' )', end="")
			self.BRACKET.display(ignoreBracket=True)

		if (self.AND):
			print(' + ', end="")
			self.AND.display()

		if (self.OR):
			print(' | ', end="")
			self.OR.display()

		if (self.XOR):
			print(' ^ ', end="")
			self.XOR.display()



class Tree(object):
	def __init__(self):
		self.left = None
		self.implie = None
		self.ifAndOnlyIf = None

	def display(self):
		self.left.display()

		if (self.implie):
			print(' => ', end="")
			self.implie.display()

		if (self.ifAndOnlyIf):
			print(' <=> ', end="")
			self.ifAndOnlyIf.display()

		print('') # \n


# A + (B | C + (D ^ X + G )) ^ Z + !V <=> V ^ Y ^ Z

tree = Tree();
tree.left = Node();
tree.left.letter = 'A'
tree.left.AND = Node();
tree.left.AND.BRACKET = Node()
tree.left.AND.BRACKET.BRACKET = Node()
tree.left.AND.BRACKET.BRACKET.letter = 'B'
tree.left.AND.BRACKET.BRACKET.OR = Node();
tree.left.AND.BRACKET.BRACKET.OR.letter = 'C'

tree.left.AND.BRACKET.BRACKET.OR.AND = Node();
tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET = Node();
tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET.BRACKET = Node();

tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET.BRACKET.letter = 'D'
tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET.BRACKET.XOR = Node()
tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET.BRACKET.XOR.letter = 'X'
tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET.BRACKET.XOR.AND = Node()
tree.left.AND.BRACKET.BRACKET.OR.AND.BRACKET.BRACKET.XOR.AND.letter = 'G'

tree.left.AND.BRACKET.XOR = Node() # Z
tree.left.AND.BRACKET.XOR.letter = 'Z'
tree.left.AND.BRACKET.XOR.AND = Node()
tree.left.AND.BRACKET.XOR.AND.letter = 'V'
tree.left.AND.BRACKET.XOR.AND.NOT = True

tree.ifAndOnlyIf = Node()
tree.ifAndOnlyIf.letter = 'V'
tree.ifAndOnlyIf.XOR = Node()
tree.ifAndOnlyIf.XOR.letter = 'Y'
tree.ifAndOnlyIf.XOR.XOR = Node()
tree.ifAndOnlyIf.XOR.XOR.letter = 'Z'

tree.display()

