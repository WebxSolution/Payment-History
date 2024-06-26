<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Task.Paymenthistoryclean
 */

namespace Joomla\Plugin\Task\Paymenthistoryclean\Extension;

use Joomla\Component\Scheduler\Administrator\Task\Status;
use Joomla\Component\Scheduler\Administrator\Event\ExecuteTaskEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Component\Scheduler\Administrator\Traits\TaskPluginTrait;

defined('_JEXEC') or die;

/**
 * Payment History Cleanup Task.
 *
 * @since 1.0.0
 */
class PaymenthistorycleanTask extends CMSPlugin implements SubscriberInterface
{
    use DatabaseAwareTrait;
    use TaskPluginTrait;
    use UserFactoryAwareTrait;
		
		/**
     * @var array Task event mappings.
     * @since 1.0.0
     */
    protected const TASKS_MAP = [
        'plg_task_payment_history_clean_execute' => [
            'langConstPrefix' => 'PLG_TASK_PAYMENTHISTORYCLEAN_EXECUTE',
            'method'          => 'execute',
            'form'            => 'executeform',
        ],
    ];

    /**
     * Autoload language for the plugin.
     *
     * @var bool
     * @since 1.0.0
     */
    protected $autoloadLanguage = true;

    /**
     * Get subscribed events for the plugin.
     *
     * @return array
     * @since 1.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onTaskOptionsList'    => 'advertiseRoutines',
            'onExecuteTask'        => 'standardRoutineHandler',
						'onContentPrepareForm' => 'enhanceTaskItemForm'
        ];
    }


    /**
     * Method to send payment history clean notification.
     *
     * @param ExecuteTaskEvent $event The execute task event.
     * @return void
     * @since 1.0.0
     */
    public function execute(ExecuteTaskEvent $event): int
    {
        try {
          
						// Get the database object
						$db     = $this->getDatabase();	
             // Get the retention period from the plugin parameters						
						$dateType = $params->retention_period_type ?? 'month';
						$dateAmount = (int) $params->retention_period_type_amount ?? 3;
            // Calculate date based on retention period
            $date = new \DateTime();
            $date->modify('-' . $dateAmount . ' '.dateType);
            $deleteDate = $db->quote($date->format('Y-m-d H:i:s'));

            // Prepare the delete query
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__rsform_paymentHistory'))
                ->where($db->quoteName('created') . ' <= ' . $deleteDate);

            $db->setQuery($query);
            $db->execute();

            return Status::OK;
        } catch (\Exception $e) {
					  $this->logTask('Error deleting old payment history: ' . $e->getMessage(), 'error');
            return Status::KNOCKOUT;
      }
		}
}
