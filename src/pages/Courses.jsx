import React, { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { 
  Search, 
  Clock, 
  Filter,
  GraduationCap,
  Wrench,
  BookOpen,
  Users,
  Briefcase,
  HardHat,
  Truck,
  MessageSquare,
  Settings,
  ArrowRight
} from "lucide-react";
import { Input } from "@/components/ui/input";
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs";

const courseCategories = [
  { id: "all", label: "All Courses", icon: BookOpen },
  { id: "level3", label: "Level III", icon: GraduationCap },
  { id: "skills", label: "Skills Award", icon: Wrench },
  { id: "short", label: "Short Courses", icon: Clock },
  { id: "soft", label: "Soft Skills", icon: Users },
  { id: "construction", label: "Construction", icon: HardHat },
  { id: "equipment", label: "Heavy Equipment", icon: Truck },
];

const allCourses = [
  // Level III Programmes
  { id: 1, title: "Level III Certificate in Electrical Engineering", category: "level3", duration: "3 months", requirement: "Minimum Grade 9", icon: Settings },
  { id: 2, title: "Level III Certificate in Bricklaying & Masonry", category: "level3", duration: "3 months", requirement: "Minimum Grade 9", icon: HardHat },
  { id: 3, title: "Level III Certificate in Metal Fabrication & Welding", category: "level3", duration: "3 months", requirement: "Minimum Grade 9", icon: Wrench },
  { id: 4, title: "Level III Food Production", category: "level3", duration: "3 months", requirement: "Minimum Grade 9", icon: BookOpen },
  { id: 5, title: "Level III General Agriculture", category: "level3", duration: "3 months", requirement: "Minimum Grade 9", icon: BookOpen },
  { id: 6, title: "Level III Fashion Design and Textile Technology", category: "level3", duration: "3 months", requirement: "Minimum Grade 9", icon: BookOpen },
  
  // Skills Award
  { id: 7, title: "Skills Award in Feed and Feed Formulation", category: "skills", duration: "8 weeks", requirement: "Open Enrollment", icon: BookOpen },
  { id: 8, title: "Skills Award in JCB Operations", category: "skills", duration: "6 weeks", requirement: "Open Enrollment", icon: Truck },
  { id: 9, title: "Skills Award in Grader General Operations", category: "skills", duration: "6 weeks", requirement: "Open Enrollment", icon: Truck },
  { id: 10, title: "Skills Award in Computer Studies", category: "skills", duration: "6 weeks", requirement: "Open Enrollment", icon: Settings },
  { id: 11, title: "Skills Award in Excavator Operations", category: "skills", duration: "6 weeks", requirement: "Open Enrollment", icon: Truck },
  { id: 12, title: "Skills Award in Refrigeration and Air Conditioning", category: "skills", duration: "3 months", requirement: "Open Enrollment", icon: Settings },
  { id: 13, title: "Skills Award in Tiling and Painting", category: "skills", duration: "3 months", requirement: "Open Enrollment", icon: HardHat },
  
  // Short Courses
  { id: 14, title: "Service and Repair of TVs", category: "short", duration: "3 weeks", requirement: "Open Enrollment", icon: Settings },
  { id: 15, title: "Service and Repair of Air Conditions", category: "short", duration: "3 weeks", requirement: "Open Enrollment", icon: Settings },
  { id: 16, title: "Service and Repair of Computers", category: "short", duration: "3 weeks", requirement: "Open Enrollment", icon: Settings },
  { id: 17, title: "Social Media Marketing Mastery", category: "short", duration: "3 weeks", requirement: "Open Enrollment", icon: MessageSquare },
  { id: 18, title: "Software Coding", category: "short", duration: "8 weeks", requirement: "Open Enrollment", icon: Settings },
  { id: 19, title: "Database Administration", category: "short", duration: "8 weeks", requirement: "Open Enrollment", icon: Settings },
  { id: 20, title: "Professional Cake Baking", category: "short", duration: "1 week", requirement: "Open Enrollment", icon: BookOpen },
  
  // Soft Skills
  { id: 21, title: "Customer Service Excellence", category: "soft", duration: "1-2 days", requirement: "Open Enrollment", icon: Users },
  { id: 22, title: "Communication Strategies", category: "soft", duration: "1-2 days", requirement: "Open Enrollment", icon: MessageSquare },
  { id: 23, title: "Time Management", category: "soft", duration: "1-2 days", requirement: "Open Enrollment", icon: Clock },
  { id: 24, title: "Leadership & Team Building", category: "soft", duration: "1-2 days", requirement: "Open Enrollment", icon: Users },
  { id: 25, title: "Business Ethics", category: "soft", duration: "1-2 days", requirement: "Open Enrollment", icon: Briefcase },
  { id: 26, title: "Emotional Intelligence", category: "soft", duration: "1-2 days", requirement: "Open Enrollment", icon: Users },
  
  // Construction
  { id: 27, title: "Construction Surveying and Setting Out", category: "construction", duration: "2 weeks", requirement: "Open Enrollment", icon: HardHat },
  { id: 28, title: "Construction Technology and Measurements", category: "construction", duration: "3 months", requirement: "Open Enrollment", icon: HardHat },
  { id: 29, title: "Construction Estimating Using Net Cost Estimator", category: "construction", duration: "1 week", requirement: "Open Enrollment", icon: HardHat },
  { id: 30, title: "Contract Administration for Construction Projects", category: "construction", duration: "1 week", requirement: "Open Enrollment", icon: HardHat },
  
  // Heavy Equipment
  { id: 31, title: "Excavator Operations Training", category: "equipment", duration: "3 weeks", requirement: "Open Enrollment", icon: Truck },
  { id: 32, title: "Grader Operations Training", category: "equipment", duration: "3 weeks", requirement: "Open Enrollment", icon: Truck },
  { id: 33, title: "TLB/JCB Operations Training", category: "equipment", duration: "3 weeks", requirement: "Open Enrollment", icon: Truck },
];

export default function Courses() {
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [searchQuery, setSearchQuery] = useState("");

  const filteredCourses = allCourses.filter(course => {
    const matchesCategory = selectedCategory === "all" || course.category === selectedCategory;
    const matchesSearch = course.title.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  const getCategoryColor = (category) => {
    const colors = {
      level3: "from-blue-500 to-blue-600",
      skills: "from-green-500 to-green-600",
      short: "from-purple-500 to-purple-600",
      soft: "from-pink-500 to-pink-600",
      construction: "from-orange-500 to-orange-600",
      equipment: "from-yellow-500 to-yellow-600"
    };
    return colors[category] || "from-gray-500 to-gray-600";
  };

  const getCategoryLabel = (category) => {
    const labels = {
      level3: "Level III Programme",
      skills: "Skills Award",
      short: "Short Course",
      soft: "Soft Skills",
      construction: "Construction",
      equipment: "Heavy Equipment"
    };
    return labels[category] || category;
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero */}
      <section className="relative h-[40vh] min-h-[300px] overflow-hidden">
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{ 
            backgroundImage: "url(https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1920&q=80)"
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/95 via-[#1e3a5f]/80 to-[#1e3a5f]/60" />
        
        <div className="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            className="text-center"
          >
            <h1 className="text-5xl md:text-6xl font-bold text-white mb-4">
              Our <span className="text-[#f5a623]">Courses</span>
            </h1>
            <p className="text-xl text-gray-300 max-w-2xl mx-auto">
              2025/2026 Short Courses, Skills Awards & Level III Programmes
            </p>
          </motion.div>
        </div>
      </section>

      {/* Filters */}
      <section className="py-8 bg-white shadow-sm sticky top-20 z-30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col lg:flex-row gap-6 items-center justify-between">
            {/* Search */}
            <div className="relative w-full lg:w-96">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
              <Input
                placeholder="Search courses..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-12 h-12 rounded-xl border-gray-200 focus:border-[#f5a623] focus:ring-[#f5a623]"
              />
            </div>

            {/* Category Tabs */}
            <div className="w-full lg:w-auto overflow-x-auto">
              <div className="flex gap-2 min-w-max">
                {courseCategories.map((cat) => (
                  <button
                    key={cat.id}
                    onClick={() => setSelectedCategory(cat.id)}
                    className={`flex items-center gap-2 px-4 py-2 rounded-xl font-medium transition-all duration-200 whitespace-nowrap ${
                      selectedCategory === cat.id
                        ? "bg-[#1e3a5f] text-white"
                        : "bg-gray-100 text-gray-600 hover:bg-gray-200"
                    }`}
                  >
                    <cat.icon className="w-4 h-4" />
                    {cat.label}
                  </button>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Course Grid */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="mb-8">
            <p className="text-gray-600">
              Showing <span className="font-semibold text-[#1e3a5f]">{filteredCourses.length}</span> courses
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <AnimatePresence mode="popLayout">
              {filteredCourses.map((course, index) => (
                <motion.div
                  key={course.id}
                  layout
                  initial={{ opacity: 0, scale: 0.9 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.9 }}
                  transition={{ delay: index * 0.02 }}
                  className="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group"
                >
                  {/* Card Header */}
                  <div className={`h-3 bg-gradient-to-r ${getCategoryColor(course.category)}`} />
                  
                  <div className="p-6">
                    {/* Category Badge */}
                    <span className={`inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r ${getCategoryColor(course.category)} text-white mb-4`}>
                      {getCategoryLabel(course.category)}
                    </span>

                    {/* Icon & Title */}
                    <div className="flex items-start gap-4 mb-4">
                      <div className="w-12 h-12 bg-[#1e3a5f]/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#f5a623]/20 transition-colors">
                        <course.icon className="w-6 h-6 text-[#1e3a5f] group-hover:text-[#f5a623] transition-colors" />
                      </div>
                      <h3 className="text-lg font-bold text-[#1e3a5f] group-hover:text-[#f5a623] transition-colors leading-snug">
                        {course.title}
                      </h3>
                    </div>

                    {/* Details */}
                    <div className="space-y-2 mb-6">
                      <div className="flex items-center gap-2 text-gray-600">
                        <Clock className="w-4 h-4 text-[#f5a623]" />
                        <span>{course.duration}</span>
                      </div>
                      <div className="flex items-center gap-2 text-gray-600">
                        <GraduationCap className="w-4 h-4 text-[#f5a623]" />
                        <span>{course.requirement}</span>
                      </div>
                    </div>

                    {/* CTA */}
                    <Link
                      to={createPageUrl("Contact")}
                      className="inline-flex items-center gap-2 text-[#1e3a5f] font-semibold hover:text-[#f5a623] transition-colors group/link"
                    >
                      Apply Now
                      <ArrowRight className="w-4 h-4 group-hover/link:translate-x-1 transition-transform" />
                    </Link>
                  </div>
                </motion.div>
              ))}
            </AnimatePresence>
          </div>

          {filteredCourses.length === 0 && (
            <div className="text-center py-16">
              <div className="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Search className="w-10 h-10 text-gray-400" />
              </div>
              <h3 className="text-xl font-semibold text-gray-700 mb-2">No courses found</h3>
              <p className="text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
          )}
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-gradient-to-br from-[#1e3a5f] to-[#2d5a8a]">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl md:text-4xl font-bold text-white mb-4">
            Ready to Start Your Journey?
          </h2>
          <p className="text-xl text-gray-300 mb-8">
            Download our application form and course list to get started
          </p>
          <div className="flex flex-wrap gap-4 justify-center">
            <a
              href="https://reti-edu.web.app/downloads/RISING%20EAST%20TRAINING%20INSTITUTE%20APPLICATION%20FORM%202025-2026.pdf"
              target="_blank"
              rel="noopener noreferrer"
              className="bg-[#f5a623] hover:bg-[#e09515] text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg"
            >
              Download Application Form
            </a>
            <Link
              to={createPageUrl("Contact")}
              className="bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 border border-white/30"
            >
              Contact Us
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}