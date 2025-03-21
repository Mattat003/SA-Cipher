class Jogo:
    def __init__(self, id, nome, descricao, preco):
        self.id = id
        self.nome = nome
        self.descricao = descricao
        self.preco = preco

    def __str__(self):
        return f"{self.nome} - {self.descricao} - R$ {self.preco}"


class Carrinho:
    def __init__(self):
        self.itens = []

    def adicionar(self, jogo):
        self.itens.append(jogo)
    
    def remover(self, jogo_id):
        self.itens = [item for item in self.itens if item.id != jogo_id]
    
    def mostrar(self):
        if not self.itens:
            print("Carrinho vazio.")
        else:
            total = 0
            for jogo in self.itens:
                print(jogo)
                total += jogo.preco
            print(f"Total: R$ {total:.2f}")


class Loja:
    def __init__(self):
        self.jogos = [
            Jogo(1, "Jogo A", "Um jogo incrível de ação.", 50),
            Jogo(2, "Jogo B", "Um jogo de aventura épico.", 40),
            Jogo(3, "Jogo C", "Um jogo de estratégia desafiador.", 60)
        ]
        self.carrinho = Carrinho()

    def listar_jogos(self):
        print("\nJogos disponíveis:")
        for jogo in self.jogos:
            print(f"{jogo.id}. {jogo.nome} - R$ {jogo.preco}")

    def mostrar_menu(self):
        while True:
            print("\n1. Ver Jogos")
            print("2. Adicionar ao Carrinho")
            print("3. Ver Carrinho")
            print("4. Remover do Carrinho")
            print("5. Sair")

            escolha = input("Escolha uma opção: ")

            if escolha == '1':
                self.listar_jogos()
            elif escolha == '2':
                jogo_id = int(input("Digite o número do jogo para adicionar ao carrinho: "))
                jogo = self.buscar_jogo_por_id(jogo_id)
                if jogo:
                    self.carrinho.adicionar(jogo)
                    print(f"{jogo.nome} adicionado ao carrinho.")
                else:
                    print("Jogo não encontrado.")
            elif escolha == '3':
                self.carrinho.mostrar()
            elif escolha == '4':
                jogo_id = int(input("Digite o número do jogo para remover do carrinho: "))
                self.carrinho.remover(jogo_id)
                print("Jogo removido do carrinho.")
            elif escolha == '5':
                print("Saindo...")
                break
            else:
                print("Opção inválida. Tente novamente.")

    def buscar_jogo_por_id(self, jogo_id):
        return next((jogo for jogo in self.jogos if jogo.id == jogo_id), None)


if __name__ == "__main__":
    loja = Loja()
    loja.mostrar_menu()
