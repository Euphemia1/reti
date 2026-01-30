import React from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion } from "framer-motion";
import { ArrowRight, Users, Headphones, Thermometer, Truck } from "lucide-react";

const courses = [
  {
    number: "01",
    title: "Customer Care Service",
    description: "Our short training equips learners with essential customer service and front office skills, focusing on communication, professionalism, and client handling.",
    image: "https://images.unsplash.com/photo-1560264280-88b68371db39?w=800&q=80",
    icon: Headphones
  },
  {
    number: "02",
    title: "Air Conditioner Repair & Installation",
    description: "This practical short course provides hands-on skills in installing, maintaining, and repairing air conditioning systems for the HVAC industry.",
    image: "https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&q=80",
    icon: Thermometer
  },
  {
    number: "03",
    title: "Excavator Operations",
    description: "Our short training equips learners with practical skills in excavator operation, safety, and maintenance for construction and earth-moving industries.",
    image: "https://images.unsplash.com/photo-1580901368919-7738efb0f87e?w=800&q=80",
    icon: Truck
  }
];

export default function FeaturedCourses() {
  return (
    <section className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <span className="text-[#f5a623] font-semibold text-sm tracking-widest uppercase mb-4 block">
            Featured Courses
          </span>
          <h2 className="text-4xl md:text-5xl font-bold text-[#1e3a5f] mb-4">
            Building Skills That Open Doors
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            To Employment and Enterprise
          </p>
        </motion.div>

        <div className="space-y-8">
          {courses.map((course, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, x: index % 2 === 0 ? -30 : 30 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              className={`flex flex-col ${index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'} gap-8 items-center`}
            >
              {/* Image */}
              <div className="lg:w-1/2">
                <div className="relative rounded-3xl overflow-hidden shadow-2xl group">
                  <img
                    src={course.image}
                    alt={course.title}
                    className="w-full h-72 lg:h-96 object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div className="absolute top-6 left-6 w-20 h-20 bg-[#f5a623] rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                    {course.number}
                  </div>
                </div>
              </div>

              {/* Content */}
              <div className="lg:w-1/2 lg:px-8">
                <div className="flex items-center gap-4 mb-4">
                  <div className="w-14 h-14 bg-[#1e3a5f] rounded-2xl flex items-center justify-center">
                    <course.icon className="w-7 h-7 text-[#f5a623]" />
                  </div>
                </div>
                <h3 className="text-3xl font-bold text-[#1e3a5f] mb-4">
                  {course.title}
                </h3>
                <p className="text-gray-600 text-lg leading-relaxed mb-6">
                  {course.description}
                </p>
                <Link
                  to={createPageUrl("Courses")}
                  className="inline-flex items-center gap-2 bg-[#1e3a5f] hover:bg-[#152a45] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 group"
                >
                  Learn More
                  <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </Link>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}