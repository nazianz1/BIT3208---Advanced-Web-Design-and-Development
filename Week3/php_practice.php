<?php
// Variables
$student = "Abdinasir Adow";
$course = "BIT3208 - Advanced Web Design";
$year = 2025;

echo "<h2>PHP Variables</h2>";
echo "<p>Student: $student</p>";
echo "<p>Course: $course</p>";
echo "<p>Year: $year</p>";

// Conditional
echo "<h2>Conditional Statement</h2>";
$marks = 75;
if ($marks >= 50) {
    echo "<p style='color:green;'>✅ Pass — Marks: $marks</p>";
} else {
    echo "<p style='color:red;'>❌ Fail — Marks: $marks</p>";
}

// Loop
echo "<h2>Loop (1 to 5)</h2>";
for ($i = 1; $i <= 5; $i++) {
    echo "<p>Item $i</p>";
}
?>