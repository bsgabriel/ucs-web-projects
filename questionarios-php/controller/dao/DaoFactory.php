<?php
abstract class DaoFactory
{
    protected abstract function getConnection();
    public abstract function getQuestaoDao();
    public abstract function getAlternativaDao();
    public abstract function getQuestionarioDao();
    public abstract function getQuestionarioQuestaoDao();
    public abstract function getOfertaDao();
}
?>