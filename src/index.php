<?php

include 'DoxyX2M/Compounds.php';

use DoxyXM\ClassCompound;
use DoxyXM\Compounds;
use DoxyXM\Compound;
use DoxyXM\DirectoryCompound;
use DoxyXM\EnumCompound;
use DoxyXM\FileCompound;
use DoxyXM\StructCompound;

$compounds = Compounds::fromXmlFile('C:\Users\yvanb\OneDrive\dox\index.xml');
/** @var Compound $compound */
?>
<pre>
## Directories
<?php foreach ($compounds->list as $compound) {
    if (!$compound instanceof DirectoryCompound) continue; ?>
 - [<?php echo $compound->getName(); ?>](<?php echo $compound->getId(); ?>)
<?php } ?>

## Files
<?php foreach ($compounds->list as $compound) {
    if (!$compound instanceof FileCompound) continue; ?>
 - [<?php echo $compound->getName(); ?>](<?php echo $compound->getId(); ?>)
<?php } ?>

## Models
<?php foreach ($compounds->list as $compound) {
    if (!$compound instanceof ClassCompound &&
        !$compound instanceof StructCompound &&
        !$compound instanceof EnumCompound) continue; ?>
 - [<?php echo $compound->getName(); ?>](<?php echo $compound->getId(); ?>)
<?php } ?>
</pre>
