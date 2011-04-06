<?php

class xnGenerateFixtureTemplateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array (
      new sfCommandOption('application', null, sfCommandOption :: PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption :: PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption :: PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));
    $this->addArgument('classname', sfCommandArgument :: REQUIRED, 'Model Class Name');

    $this->namespace = 'dev';
    $this->name = 'fixture-template';
    $this->briefDescription = '';
    $this->detailedDescription =<<<EOF
The [fixture-template|INFO] task does things.
Call it with:

  [php symfony dev:fixture-template|INFO]
EOF;
  }

  protected function execute($arguments = array (), $options = array ())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $MODEL_CLASS_NAME = $arguments['classname'];

    if ('doctrine' === sfConfig::get('sf_orm'))
    {
      $table = Doctrine_Core::getTable($MODEL_CLASS_NAME);
      $fieldNames = $table->getColumnNames();

      $this->log(sprintf('%s', $MODEL_CLASS_NAME));
      $this->log(sprintf('  %s_data', $MODEL_CLASS_NAME));

      foreach ($fieldNames as $fieldName) {
        $this->log(sprintf('    %-20s %s', $fieldName.':', $table->getTypeOf($fieldName)));
      }
    }
    else
    {
      $MODEL_CLASS_NAME_PEER = $MODEL_CLASS_NAME . 'Peer';
      $peerClass = new $MODEL_CLASS_NAME_PEER ();
      $fieldNames = $peer_class->getFieldNames(BasePeer :: TYPE_FIELDNAME);

      $this->log(sprintf('%s', $MODEL_CLASS_NAME));
      $this->log(sprintf('  %s_data', $MODEL_CLASS_NAME));

      foreach ($fieldNames as $fieldName) {
        $this->log(sprintf('    %-20s', $fieldName.':'));
      }
    }
  }
}

