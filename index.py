import tkinter as tk
from tkinter import Label
from PIL import Image, ImageTk


class Logo:
    def __init__(self, root, caminho_imagem, tamanho=(200, 200), posicao=(0.5, 0.3)):
        self.root = root
        self.caminho_imagem = caminho_imagem
        self.tamanho = tamanho
        self.posicao = posicao
        self.logo = None
        self.carregar_logo()

    def carregar_logo(self):
        """Carrega a imagem do logo e exibe na tela."""
        try:
            logo_imagem = Image.open(self.caminho_imagem)  # Caminho correto para a imagem
            logo_imagem = logo_imagem.resize(self.tamanho, Image.LANCZOS)  # Substituindo ANTIALIAS por LANCZOS
            self.logo = ImageTk.PhotoImage(logo_imagem)  # Converte a imagem para o formato Tkinter

            # Exibe a imagem no Label e centraliza o logo
            logo_label = tk.Label(self.root, image=self.logo, bg="#5bc0be")
            logo_label.place(relx=self.posicao[0], rely=self.posicao[1], anchor="center")
        except Exception as e:
            print(f"Erro ao carregar a imagem: {e}")  # Exibe erro caso o carregamento falhe


class App:
    def __init__(self, root):
        self.root = root
        self.root.title("Cipher App")
        self.root.geometry("600x300")
        self.root.configure(bg="#5bc0be")

        # Criação do fundo
        self.fundo = tk.Frame(self.root, bg="#5bc0be", width=550, height=250)
        self.fundo.place(relx=0.5, rely=0.5, anchor="center")

        # Carrega e exibe o logo
        self.logo = Logo(self.fundo, "img/logo.png")

        # Mensagem de agradecimento com fonte "Verdana" e centralizada
        self.mensagem = Label(self.fundo, text="Obrigado por baixar nosso aplicativo!\nAproveite nossa plataforma!",
                              font=("Verdana", 14, "bold"), fg="black", bg="#5bc0be")
        self.mensagem.place(relx=0.5, rely=0.45, anchor="center")

        # Contato com fonte "Times New Roman" e centralizado
        self.contato_label = Label(self.fundo, text="Contato:", font=("Times New Roman", 12), fg="black", bg="#5bc0be")
        self.contato_label.place(relx=0.5, rely=0.6, anchor="center")

        self.contato_email = Label(self.fundo, text="CIPHER@gmail.com", font=("Courier New", 10), fg="black", bg="#5bc0be")
        self.contato_email.place(relx=0.5, rely=0.75, anchor="center")


if __name__ == "__main__":
    root = tk.Tk()
    app = App(root)
    root.mainloop()
