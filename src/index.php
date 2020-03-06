<?php

include 'DoxyX2M/Compounds.php';

use DoxyXM\ClassCompound;
use DoxyXM\Compounds;
use DoxyXM\Compound;
use DoxyXM\Member;
use DoxyXM\DirectoryCompound;
use DoxyXM\EnumCompound;
use DoxyXM\FileCompound;
use DoxyXM\StructCompound;

$compounds = Compounds::fromXmlFile('C:\Users\yvanb\OneDrive\dox\index.xml');
/** @var Compound $compound */
?>
<pre>
## Directories
<?php
foreach ($compounds->list as $compound) {
    switch (TRUE) {
        case $compound instanceof DirectoryCompound:
            echo " - [" . $compound->getName() . "](" . $compound->getId() . ")" . PHP_EOL;
            break;
    }
}
?>

## Files
<?php foreach ($compounds->list as $compound) {
    if (!$compound instanceof FileCompound) continue; ?>
 - [<?php echo $compound->getName(); ?>](<?php echo $compound->getId(); ?>)
<?php } ?>

## Classes
<?php
foreach ($compounds->list as $compound) {
    switch (TRUE) {
        case $compound instanceof ClassCompound:
        case $compound instanceof StructCompound:
        case $compound instanceof EnumCompound:
            echo " - [" . $compound->getName() . "](" . $compound->getId() . ")" . PHP_EOL;
            /** @var Member $member */
            foreach ($compound->getMembers() as $member) {
                echo "   - [" . $member->getName() . "](" . $member->getId() . ")" . PHP_EOL;
            }
            break;
    }
}
?>
</pre>
