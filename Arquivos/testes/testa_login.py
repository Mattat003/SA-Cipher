from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import random
import time

driver = webdriver.Chrome()
wait = WebDriverWait(driver, 10)

try:
    # 1) Abre a página de login
    driver.get("http://localhost:8080/SA-Cipher/Arquivos/PHP/login.php")
    time.sleep(2)  # espera 2s para a página carregar e vc ver

    # 2) Clica no link "Criar conta"
    wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Criar conta"))).click()
    time.sleep(2)  # espera 2s para página de cadastro abrir

    # 3) Espera o formulário aparecer e preenche
    wait.until(EC.visibility_of_element_located((By.NAME, "nome")))

    random_number = random.randint(1000, 9999)
    nome_teste = "Teste Selenium"
    email_teste = f"selenium_teste{random_number}@example.com"
    senha_teste = "SenhaForte123"

    driver.find_element(By.NAME, "nome").send_keys(nome_teste)
    time.sleep(1)  # delay entre preenchimento
    driver.find_element(By.NAME, "email").send_keys(email_teste)
    time.sleep(1)
    driver.find_element(By.NAME, "senha").send_keys(senha_teste)
    time.sleep(1)
    driver.find_element(By.NAME, "confirma-senha").send_keys(senha_teste)
    time.sleep(1)

    # 4) Clica no botão "Cadastrar-se"
    driver.find_element(By.XPATH, "//input[@value='Cadastrar-se']").click()
    time.sleep(2)  # espera o envio processar

    # 5) Espera alerta e aceita
    wait.until(EC.alert_is_present())
    alert = driver.switch_to.alert
    print("Alerta cadastro:", alert.text)
    alert.accept()
    time.sleep(2)

    # 6) Espera redirecionar para login.php
    wait.until(EC.url_contains("login.php"))
    time.sleep(2)

    # 7) Preenche login
    wait.until(EC.visibility_of_element_located((By.ID, "email")))
    driver.find_element(By.ID, "email").send_keys(email_teste)
    time.sleep(1)
    driver.find_element(By.ID, "senha").send_keys(senha_teste)
    time.sleep(1)

    # 8) Clica em "Entrar"
    driver.find_element(By.TAG_NAME, "button").click()

    # 9) Espera redirecionar para página interna
    wait.until(EC.url_changes("http://localhost:8080/SA-Cipher/Arquivos/PHP/login.php"))
    print("Login realizado! URL atual:", driver.current_url)
    time.sleep(5)  # tempo para ver a página logada

finally:
    driver.quit()
