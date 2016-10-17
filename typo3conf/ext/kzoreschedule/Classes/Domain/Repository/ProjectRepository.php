<?php
namespace AmosCalamida\Kzoreschedule\Domain\Repository;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Amos Calamida <amos@calamida.ch>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The repository for Projects
 */
class ProjectRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * query findByPublicAndCourses
     *
     * Finds all Repository Objects with progress either 1,2 or 3 and the passed Courses in their Change Objects
     *
     * @param array $teacherCourses All Courses, that a teacher may view Changes
     *
     * @return object
     */
    public function findByPublicAndCourses(array $teacherCourses)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd($query->in('progress', array(1, 2, 3)), $query->in('changes.course_id', $teacherCourses))
        );
        return $query->execute();
    }



}