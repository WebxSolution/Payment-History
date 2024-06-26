<?php
/**
 * @package RSForm!Pro
 * @copyright (C) 2024 webx.solutions
 * @license GPL, https://www.gnu.org/licenses/gpl-3.0.html#license-text
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Date\Date;

class PlgSystemRsfppaymenthistory extends CMSPlugin
{
    protected $app;

    public function onRsformAfterConfirmPayment($submissionId)
    {
        // Get database object
        $db = Factory::getDbo();

        // Query to fetch submission details including UserId
        $query = $db->getQuery(true)
            ->select([
                'sub.UserId',
                'vals.FieldName',
                'vals.FieldValue'
            ])
            ->from($db->quoteName('#__rsform_submissions', 'sub'))
            ->leftJoin($db->quoteName('#__rsform_submission_values', 'vals') . ' ON sub.SubmissionId = vals.SubmissionId')
            ->where('sub.SubmissionId = ' . $db->quote($submissionId));

        $db->setQuery($query);
        $results = $db->loadAssocList();

        // Initialize variables
        $userId = null;
        $customerName = null;
        $checkstorun = null;
        $amount = null;
        $paymentMethod = null;
        $created = new Date();

        // Extract necessary data from results
        foreach ($results as $row) {
            switch ($row['FieldName']) {
                case 'forename':
                    $forename = $row['FieldValue'];
                    break;
                case 'middle':
                    $middle = $row['FieldValue'];
                    break;
                case 'surname':
                    $surname = $row['FieldValue'];
                    break;
                case 'checkstorun':
                    $checkstorun = $row['FieldValue'];
                    break;
                case 'rsfp_total':
                    $amount = $row['FieldValue'];
                    break;
                case 'choose_payment':
                    $paymentMethod = $row['FieldValue'];
                    break;
            }
        }

        // Concatenate names to form customer_name
        $customerName = trim($forename. ($middle ? ' '.$middle: '').' '.$surname);

        // Fetch UserId separately
        $userId = $results[0]['UserId']; // Since UserId is fetched directly from #__rsform_submissions

        // Check if all required data is available
        if ($userId && $customerName && $checkstorun && $paymentMethod && $amount) {
            // Prepare the insert query
            $columns = ['id', 'UserId', 'customer_name', 'checkstorun', 'payment_method', 'amount', 'created', 'modified'];
            $values = [
                $db->quote($submissionId),
                $db->quote($userId),
                $db->quote($customerName),
                $db->quote($checkstorun),
                $db->quote($paymentMethod),
                $db->quote($amount),
                $db->quote($created->toSql()),
                $db->quote($created->toSql())
            ];

            $query = $db->getQuery(true)
                        ->insert($db->quoteName('#__rsform_paymentHistory'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));
            
            // Execute the query
            $db->setQuery($query);
            $db->execute();
        }
    }    
}