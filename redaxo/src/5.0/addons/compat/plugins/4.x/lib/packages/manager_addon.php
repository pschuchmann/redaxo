<?php

class rex_addonManagerCompat extends rex_addonManager
{
  public function install($installDump = TRUE)
  {
    $state = parent::install($installDump);

    // Dateien kopieren
    $files_dir = $this->package->getBasePath('files');
    if($state === TRUE && is_dir($files_dir))
    {
      if(!rex_dir::copy($files_dir, $this->package->getAssetsPath()))
      {
        $state = $this->I18N('install_cant_copy_files');
      }
    }

    return $state;
  }

  public function includeFile($file)
  {
    global $REX;

    $this->package->includeFile($file, array('REX_USER', 'REX_LOGIN', 'I18N', 'article_id', 'clang'));

    if(isset($REX['ADDON']) && is_array($REX['ADDON']))
    {
      foreach($REX['ADDON'] as $property => $propertyArray)
      {
        foreach($propertyArray as $addonName => $value)
        {
          if($addonName == $this->package->getName())
          {
            $this->package->setProperty($property, $value);
          }
        }
      }
    }
  }
}