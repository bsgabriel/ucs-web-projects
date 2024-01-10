<?php

/**
 * Classe criada para armazenar valores constantes, para acessar valores de sessão ou cookies.
 * @todo trocar o nome delas por algo que não seja fácil de identificar ao acessar os cookies.
 */
class GlobalKeys
{
  /** 
   * Chave que armazena o tipo de usuário autenticado
   */
  const TIPO_USUARIO_AUTENTICADO = "TIPO_USUARIO_AUTENTICADO";

  /** 
   * Chave que armazena o ID do usuário autenticado
   */
  const ID_USUARIO_AUTENTICADO = "ID_USUARIO_AUTENTICADO";

  /**
   * Chave para tipo de questão discursiva
   */
  const TIP_QUESTAO_DISCURSIVA = "D";

  /**
   * Chava para tipo de questão de múltiplas escolhas
   */
  const TIP_QUESTAO_MULTIPLA_ESCOLHA = "M";

  /**
   * Chave para tipo de questão de veradeiro ou falso
   */
  const TIP_QUESTAO_VERDADEIRO_FALSO = "V";
}
?>