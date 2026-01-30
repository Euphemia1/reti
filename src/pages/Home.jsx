import React from "react";
import HeroSection from "@/components/home/HeroSection";
import MottoSection from "@/components/home/MottoSection";
import ProgramsSection from "@/components/home/ProgramsSection";
import FeaturedCourses from "@/components/home/FeaturedCourses";
import CTASection from "@/components/home/CTASection";
import ContactFormSection from "@/components/home/ContactFormSection";

export default function Home() {
  return (
    <div>
      <HeroSection />
      <MottoSection />
      <ProgramsSection />
      <FeaturedCourses />
      <CTASection />
      <ContactFormSection />
    </div>
  );
}