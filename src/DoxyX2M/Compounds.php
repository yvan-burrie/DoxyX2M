<?php

/**
 * @author Yvan Burrie
 */

namespace DoxyXM;

use \UnexpectedValueException, \InvalidArgumentException;
use \SimpleXMLElement, \SplDoublyLinkedList;

/**
 * @package DoxyXM
 */
class Compounds
{
    /**
     * @var SimpleXMLElement
     */
    protected $xmlRoot;

    public function getXmlRoot(): SimpleXMLElement
    {
        return $this->xmlRoot;
    }

    /**
     * @var SplDoublyLinkedList
     */
    public $list;

    protected function __construct()
    {
        $this->list = new SplDoublyLinkedList();
    }

    protected function setupXml(SimpleXMLElement $rootBuffer)
    {
        $this->xmlRoot = $rootBuffer;

        if (NULL === $rootBuffer) {
            throw new UnexpectedValueException();
        }
        if ('doxygenindex' != $rootBuffer->getName()) {
            throw new UnexpectedValueException();
        }
        foreach ($rootBuffer as $compoundBuffer) {
            if ($compoundBuffer instanceof SimpleXMLElement) {
                if (0 === strcmp('compound', $compoundBuffer->getName())) {
                    $this->list[] = static::makeCompoundFromXmlBuffer($compoundBuffer);
                }
            }
        }
    }

    public static function fromXmlDoc(string $fileContent): self
    {
        $compounds = new static();
        $compounds->setupXml(new SimpleXMLElement($fileContent));
        return $compounds;
    }

    public static function fromXmlFile(string $fileName): self
    {
        $fileContent = file_get_contents($fileName);
        $compounds = static::fromXmlDoc($fileContent);
        return $compounds;
    }

    public static function makeCompoundFromXmlBuffer(SimpleXMLElement $compoundBuffer): Compound
    {
        if (NULL === $compoundBuffer) {
            throw new InvalidArgumentException();
        }
        $compoundKind = $compoundBuffer->attributes()->{'kind'}->__toString() ?? NULL;
        if (!is_string($compoundKind)) {
            throw new UnexpectedValueException();
        }
        /** @var Compound $compound */
        $compound = NULL;
        switch ($compoundKind) {
            case 'enum':
                $compound = EnumCompound::fromXmlBuffer($compoundBuffer);
                break;
            case 'struct':
                $compound = StructCompound::fromXmlBuffer($compoundBuffer);
                break;
            case 'class':
                $compound = ClassCompound::fromXmlBuffer($compoundBuffer);
                break;
            case 'namespace':
                $compound = NamespaceCompound::fromXmlBuffer($compoundBuffer);
                break;
            case 'page':
                $compound = PageCompound::fromXmlBuffer($compoundBuffer);
                break;
            case 'dir':
                $compound = DirectoryCompound::fromXmlBuffer($compoundBuffer);
                break;
            case 'file':
                $compound = FileCompound::fromXmlBuffer($compoundBuffer);
                break;
            default:
                throw new UnexpectedValueException();
        }
        return $compound;
    }
}

/**
 * @package DoxyXM
 */
abstract class Entity
{
    /**
     * @var string
     */
    protected $id;

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @var SplDoublyLinkedList
     */
    protected $names;

    public function getName(): string
    {
        if (count($this->names) > 0) {
            return $this->names[0];
        } else {
            return '';
        }
    }

    protected function __construct()
    {
        $this->names = new SplDoublyLinkedList();
    }

    public static function fromXmlBuffer(SimpleXMLElement $compoundBuffer): self
    {
        $entity = new static();
        $entity->id = $compoundBuffer->attributes()->{'refid'}->__toString();
        $entity->names[] = $compoundBuffer->{'name'}->__toString();
        return $entity;
    }
}

/**
 * @package DoxyXM
 */
abstract class Compound extends Entity
{
    /**
     * @var SplDoublyLinkedList
     */
    protected $members;

    protected function __construct()
    {
        parent::__construct();

        $this->members = new SplDoublyLinkedList();
    }

    public static function fromXmlBuffer(SimpleXMLElement $compoundBuffer): Entity
    {
        /** @var static $compound */
        $compound = parent::fromXmlBuffer($compoundBuffer);

        $compoundMembersBuffer = $compoundBuffer->{'member'} ?? NULL;
        if ($compoundMembersBuffer instanceof SimpleXMLElement) {
            foreach ($compoundMembersBuffer as $memberBuffer) {
                $memberKind = $memberBuffer->attributes()->{'kind'}->__toString();
                $member = NULL;
                switch ($memberKind) {
                    case 'enum':
                        $member = EnumMember::fromXmlBuffer($memberBuffer);
                        break;
                    case 'enumvalue':
                        $member = EnumValueMember::fromXmlBuffer($memberBuffer);
                        break;
                    case 'typedef':
                        $member = TypeDefMember::fromXmlBuffer($memberBuffer);
                        break;
                    case 'variable':
                        $member = VariableMember::fromXmlBuffer($memberBuffer);
                        break;
                    case 'function':
                        $member = FunctionMember::fromXmlBuffer($memberBuffer);
                        break;
                    case 'class':
                        $member = ClassMember::fromXmlBuffer($memberBuffer);
                        break;
                    case 'define':
                        $member = DefineMember::fromXmlBuffer($memberBuffer);
                        break;
                    default:
                        throw new UnexpectedValueException();
                }
                $compound->members[] = $member;
            }
        }
        return $compound;
    }
}

class EnumCompound extends Compound
{
}

class StructCompound extends Compound
{

}

class ClassCompound extends Compound
{

}

class NamespaceCompound extends Compound
{

}

class PageCompound extends Compound
{

}

class DirectoryCompound extends Compound
{

}

class FileCompound extends Compound
{

}

abstract class Member
{
    public static function fromXmlBuffer(SimpleXMLElement $memberBuffer): self
    {
        $member = new static();
        return $member;
    }
}

class VariableMember extends Member
{

}

class EnumMember extends Member
{

}

class EnumValueMember extends Member
{

}

class TypeDefMember extends Member
{

}

class ClassMember extends Member
{

}

class FunctionMember extends Member
{

}

class DefineMember extends Member
{

}
