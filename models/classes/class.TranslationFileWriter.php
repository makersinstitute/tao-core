<?php

error_reporting(E_ALL);

/**
 * A Writing class for TranslationFiles. Must be implemented by a concrete class
 * a given Translation Format such as XLIFF, PO, ... The write method must be
 * by subclasses.
 *
 * @author Jerome Bogaerts
 * @package tao
 * @since 2.2
 * @subpackage models_classes
 * @version 1.0
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034CC-includes begin
// section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034CC-includes end

/* user defined constants */
// section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034CC-constants begin
// section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034CC-constants end

/**
 * A Writing class for TranslationFiles. Must be implemented by a concrete class
 * a given Translation Format such as XLIFF, PO, ... The write method must be
 * by subclasses.
 *
 * @abstract
 * @access public
 * @author Jerome Bogaerts
 * @package tao
 * @since 2.2
 * @subpackage models_classes
 * @version 1.0
 */
abstract class tao_models_classes_TranslationFileWriter
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute filePath
     *
     * @access private
     * @var string
     */
    private $filePath = '';

    /**
     * Short description of attribute translationFile
     *
     * @access private
     * @var TranslationFile
     */
    private $translationFile = null;

    // --- OPERATIONS ---

    /**
     * Creates a new instance of TranslationFileWriter.
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string filePath
     * @param  TranslationFile translationFile
     * @return mixed
     */
    public function __construct($filePath,  tao_models_classes_TranslationFile $translationFile)
    {
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034D3 begin
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034D3 end
    }

    /**
     * Reads a translation file to persist a TranslationFile in a specific
     * Subclasses must implement this method to meet the requirement of the
     * they support such as XLIFF or PO files.
     *
     * @abstract
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return mixed
     */
    public abstract function write();

    /**
     * Sets the TranslationFile that has to be serialized.
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  TranslationFile translationFile
     * @return mixed
     */
    public function setTranslationFile( tao_models_classes_TranslationFile $translationFile)
    {
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034D9 begin
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034D9 end
    }

    /**
     * Gets the location where the file must be written.
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return string
     */
    public function getFilePath()
    {
        $returnValue = (string) '';

        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034DC begin
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034DC end

        return (string) $returnValue;
    }

    /**
     * Sets the location where the file has to be written.
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  string filePath
     * @return mixed
     */
    public function setFilePath($filePath)
    {
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034DE begin
        // section 10-13-1-85-72d0ca97:1331b62f595:-8000:00000000000034DE end
    }

} /* end of abstract class tao_models_classes_TranslationFileWriter */

?>